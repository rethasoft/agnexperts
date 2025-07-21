<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMoneyFormat;

// Email
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusChangedMail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Keuringen extends Model
{
    use HasFactory;
    use HasMoneyFormat;

    protected $fillable = [
        // Basic information
        'tenant_id',
        'client_id',
        'employee_id',
        'file_id',
        'status',
        'status_id',

        // Personal/Company details
        'name',
        'email',
        'phone',
        'company_name',
        'vat_number',

        // Main address
        'street',
        'number',
        'box',
        'floor',
        'postal_code',
        'city',
        'province',
        'province_id',

        // Billing address
        'has_billing_address',
        'billing_street',
        'billing_number',
        'billing_box',
        'billing_postal_code',
        'billing_city',

        // Financial information
        'total',
        'tax',
        'subtotal',
        'paid',
        'payment_status',

        // Additional information
        'inspection_date',
        'text',
    ];
    protected $attributes = ['status' => 1];

    protected $casts = [
        'total' => 'float',
        'tax' => 'float',
        'subtotal' => 'float',
        'has_billing_address' => 'boolean',
    ];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('guard', function ($builder) {

            if (auth()->check()) {
                if (auth()->user()->type == 'tenant') {
                    $builder->where('tenant_id', auth()->user()->id);
                }
                if (auth()->user()->type == 'employee') {
                    $builder->where('employee_id', auth()->user()->employee->id);
                }
                if (auth()->user()->type == 'client') {
                    $builder->where('client_id', auth()->user()->client->id);
                }
            }
        });

        static::creating(function ($model) {
            $lastFileId = self::max('file_id');

            if ($lastFileId && preg_match('/^(\d+)-(\d{4})$/', $lastFileId, $matches)) {
                $nextFileId = (int)$matches[1] + 1;
            } else {
                $nextFileId = $lastFileId ? ((int)$lastFileId + 1) : 1;
            }
            $year = date('Y');
            $model->file_id = str_pad($nextFileId, 3, '0', STR_PAD_LEFT) . '-' . $year;
        });
    }

    public function files()
    {
        return $this->hasMany(File::class, 'object_id', 'id');
    }
    public function getStatus()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'object_id', 'id')->orderByDesc('id');
    }
    public function types()
    {
        if ($this->type != Null)
            return json_decode($this->type, true);
        else
            return [];
    }
    public function getTotal()
    {
        $result = 0;
        if ($this->type != Null) {
            $ids = json_decode($this->type, true);
            if (is_array($ids)) {
                $result = Type::whereIn('id', $ids)->sum('price');
                if ($result !== null) {
                    return '€' . $result;
                }
            }
        }

        return '€' . $result;
    }

    public function totalIncome()
    {
        $total = Keuringen::where('tenant_id', getTenantId())
            ->where('paid', 1)
            ->with('details') // Eager load the 'details' relationship
            ->get()
            ->sum(function ($keuringen) {
                return $keuringen->details->sum('total');
            }) ?? 0; // Return 0 if the sum is null

        $totalFormatted = '€' . number_format($total, 2, ',', '.'); // Format the sum as Euro currency

        return $totalFormatted; // Output the formatted total with Euro symbol

    }

    public function details()
    {
        return $this->hasMany(KeuringenDetail::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->orderByDesc('id');
    }

    public function event()
    {
        return $this->hasOne(EmployeEvent::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function updateOrCreateEvent($employee_id, $date)
    {
        if ($employee_id > 0) {
            $start = $date > 0 ? $date : $this->getLastEventTime();
            $end = Carbon::parse($start)->addMinutes(45);

            $employe = Employee::find($employee_id);
            $adres = $this->street . ' ' . $this->number . ' ' . $this->postal_code . ' ' . $this->district;
            $title = $employe->name . ' ' . $employe->surname . ' - ' . $adres;


            $employe_schedule = EmployeEvent::updateOrCreate(['keuringen_id' => $this->id], [
                'keuringen_id' => $this->id,
                'employee_id' => $employee_id,
                'title' => $title,
                'start' => $date,
                'end' => $end
            ]);

            if (!$employe_schedule) {
                return ['status', 'error', 'msg' => 'Opdracht kon niet worden uitgevoerd'];
            }
            return ['status' => 'success'];
        } else {
            EmployeEvent::where(['keuringen_id' => $this->id])->delete();
            return ['status' => 'success'];
        }
    }
    public function getLastEventTime()
    {
        $latestEndDate = EmployeEvent::where('employee_id', $this->employee_id)
            ->latest('end')
            ->value('end');

        if ($latestEndDate) {
            return Carbon::parse($latestEndDate)->addMinutes(20);
        }
        return date('Y-m-d H:i:s');
    }

    public function getAll()
    {
        $user = auth()->user();
        $userType = $user->type ?? null;

        if ($userType === 'tenant') {
            return Keuringen::where('tenant_id', getTenantId())->get();
        }
        if ($userType === 'client') {
            return Keuringen::where('client_id', getClientId())->get();
        }
        if ($userType === 'employee') {
            return Keuringen::where('employee_id', $user->employee->id)->get();
        }
    }

    public function saveFiles($table, $files)
    {
        if (is_array($files)) {
            foreach ($files as $file) {
                if (!$this->saveFile($table, $file)) {
                    return false;
                }
            }
        } else {
            return $this->saveFile($table, $files);
        }
        return true;
    }

    protected function saveFile($table, $file)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = Str::slug($originalName, '-') . '.' . $file->getClientOriginalExtension();
        $fileModel = new File();
        $savedFile = $fileModel->create([
            'object_id' => $this->id,
            'table' => $table,
            'path' => '/img/files/',
            'name' => $filename,
            'type' => $file->getClientOriginalExtension()
        ]);

        if (!$savedFile) {
            return false; // Return false if file creation fails
        }

        $store = $file->storeas('files/', $filename, ['disk' => 'public_folder']);
        if (!$store) {
            return false;
        }
        return true;
    }

    public function getInvoiceFile()
    {
        return $this->hasOne(File::class, 'object_id', 'id')->where('table', 'Invoice')->orderByDesc('id');
    }

    public function provincie()
    {
        return $this->hasOne(Provincie::class, 'id', 'province_id');
    }

    /**
     * Get formatted total amount
     *
     * @return string
     */
    public function getFormattedTotalAttribute(): string
    {
        return $this->getMoneyFormat('total');
    }

    /**
     * Get formatted tax amount
     *
     * @return string
     */
    public function getFormattedTaxAttribute(): string
    {
        return $this->getMoneyFormat('tax');
    }

    /**
     * Get formatted subtotal amount
     *
     * @return string
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return $this->getMoneyFormat('subtotal');
    }
}
