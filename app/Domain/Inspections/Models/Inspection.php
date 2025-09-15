<?php

namespace App\Domain\Inspections\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMoneyFormat;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Province;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Domain\Invoices\Models\Invoice;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Domain\Events\Models\Event;
use App\Domain\Inspections\Enums\InspectionSource;

class Inspection extends Model
{
    use HasMoneyFormat, Notifiable;

    protected $table = 'inspections';

    protected $fillable = [
        // Basic information
        'tenant_id',
        'client_id',
        'employee_id',
        'file_id',
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
        'sub_total',
        'paid',
        'payment_status',

        // Combi indirim alanları
        'combi_discount_id',
        'combi_discount_type',
        'combi_discount_value',
        'combi_discount_amount',

        // Additional information
        'inspection_date',
        'text',
        'source',
    ];

    protected $casts = [
        'has_billing_address' => 'boolean',
        'paid' => 'boolean',
        'total' => 'float',
        'tax' => 'float',
        'sub_total' => 'float',
        'inspection_date' => 'datetime',
        'source' => InspectionSource::class,
        // Combi indirim alanları
        'combi_discount_id' => 'integer',
        'combi_discount_value' => 'float',
        'combi_discount_amount' => 'float',
    ];

    // Scopes
    public function scopeLatest(Builder $query, int $limit = 10): Builder
    {
        return $query->orderByDesc('id')->limit($limit);
    }

    public function scopeWithStatus(Builder $query): Builder
    {
        return $query->selectRaw('count(*) as count, status')
            ->groupBy('status');
    }

    public function scopeForPeriod(Builder $query, string $period): Builder
    {
        return match ($period) {
            'today' => $query->whereDate('created_at', Carbon::today()),
            'week' => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
            'month' => $query->whereMonth('created_at', Carbon::now()->month),
            'year' => $query->whereYear('created_at', Carbon::now()->year),
            default => $query
        };
    }

    // İstatistik Metodları
    public static function getStatusStatistics(): Collection
    {
        $inspections = self::withStatus()->get();
        $statuses = collect();

        foreach ($inspections as $inspection) {
            $status = Status::find($inspection->status);
            if ($status) {
                $statuses[$status->name] = [
                    'count' => $inspection->count,
                    'color' => $status->color
                ];
            }
        }

        return $statuses;
    }

    public static function getDashboardStatistics(): array
    {
        return [
            'total_count' => self::count(),
            'total_income' => (new self)->totalIncome(),
            'recent' => self::latest()->get(),
            'statuses' => self::getStatusStatistics()
        ];
    }

    public function totalIncome(string $period = null): float
    {
        $query = $this->newQuery();

        if ($period) {
            $query->forPeriod($period);
        }

        return $query->sum('total') ?? 0.00;
    }

    // Para Formatı Yardımcıları
    public function getFormattedSubTotalAttribute(): string
    {
        return $this->formatMoney($this->sub_total);
    }

    public function getFormattedTaxAttribute(): string
    {
        return $this->formatMoney($this->tax);
    }

    public function getFormattedTotalAttribute(): string
    {
        return $this->formatMoney($this->total);
    }

    // İlişkiler
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function items()
    {
        return $this->hasMany(InspectionItem::class);
    }

    // Tüm dosyalar için genel ilişki
    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // Özel dosya tipleri için helper methodlar
    public function adminDocuments()
    {
        return $this->files()->whereJsonContains('metadata->type', File::TYPE_ADMIN_DOCUMENTS);
    }

    public function customerDocuments()
    {
        return $this->files()->whereJsonContains('metadata->type', File::TYPE_CUSTOMER_DOCUMENTS);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function province()
    {
        return $this->hasOne(Province::class, 'id', 'province_id');
    }

    public function combiDiscount()
    {
        return $this->belongsTo(CombiDiscount::class);
    }

    // Get total excluding offerte items
    public function getNormalTotalAttribute(): float
    {
        return $this->items()
            ->where('is_offerte', false)
            ->get()
            ->sum(function($item) {
                return $item->total ?? ($item->price * $item->quantity);
            });
    }

    // Get total including offerte items (for display purposes)
    public function getDisplayTotalAttribute(): float
    {
        return $this->items()
            ->get()
            ->sum(function($item) {
                if ($item->is_offerte) {
                    return 0; // Offerte items don't contribute to total
                }
                return $item->total ?? ($item->price * $item->quantity);
            });
    }

    public function getFormattedAddressAttribute()
    {
        return implode(' ', array_filter([
            $this->street,
            $this->number,
            $this->number_addition,
            $this->postal_code,
            $this->city
        ]));
    }

    /**
     * Get admin uploaded files for the inspection
     */
    public function adminFiles()
    {
        return $this->morphMany(File::class, 'fileable')
            ->where('metadata->type', 'admin');
    }

    /**
     * Get all events for the inspection.
     */
    public function events(): MorphMany
    {
        return $this->morphMany(Event::class, 'eventable');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // File ID generation
            $lastFileId = self::max('file_id');
            $year = date('Y');

            if ($lastFileId && preg_match('/^(\d+)-(\d{4})$/', $lastFileId, $matches)) {
                $nextFileId = (int)$matches[1] + 1;
            } else {
                $nextFileId = $lastFileId ? ((int)$lastFileId + 1) : 1;
            }
            $model->file_id = str_pad($nextFileId, 3, '0', STR_PAD_LEFT) . '-' . $year;

            // Province handling from cache
            if ($model->province_id) {
                $provinces = Cache::get('all_provinces');
                if ($provinces && isset($provinces[$model->province_id])) {
                    $model->province = $provinces[$model->province_id];
                } else {
                    Log::warning('Province not found in cache', [
                        'province_id' => $model->province_id
                    ]);
                }
            }
        });
    }

    /**
     * Scope query to only include inspections accessible by the current authenticated user
     * 
     * This scope filters inspections based on the user's guard type:
     * - For tenants: Shows inspections where tenant_id matches user ID
     * - For clients: Shows inspections where client_id matches user ID  
     * - For employees: Shows inspections where employee_id matches user ID
     * - For others: Returns unfiltered query
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser(Builder $query)
    {
        $user = auth()->user();

        return match (true) {
            auth()->guard('tenant')->check() => $query->where('tenant_id', $user->id),
            auth()->guard('client')->check() => $query->where('client_id', $user->id),
            auth()->guard('employee')->check() => $query->where('employee_id', $user->id),
            default => $query,
        };
    }

    /**
     * Check if the current authenticated user has access to this inspection
     * 
     * Uses the forUser scope to determine if the current user can access this specific inspection
     *
     * @return bool
     */
    public function userHasAccess(): bool
    {
        return static::where('id', $this->id)
            ->forUser()
            ->exists();
    }
}
