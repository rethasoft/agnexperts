<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use App\Domain\DocumentManagement\Models\Document;
use App\Domain\Inspections\Models\Inspection;
use Illuminate\Support\Facades\DB;


class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $fillable = [
        'tenant_id',
        'role_id',
        'client_id',
        'name',
        'surname',
        'password',
        'email',
        'phone',
        'address',
        // 'places', 
        'auth_id'
    ];
    /**
     * The attributes that should be hidden for serialization
     * 
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     *  The attributes that should be cast
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'password' => "hashed"
    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    // public function tasks()
    // {
    //     return $this->hasMany(Task::class);
    // }

    // public function workHours()
    // {
    //     return $this->hasMany(WorkHour::class);
    // }

    // Performance metrics
    public function getPerformanceMetrics()
    {
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        // Get status names from the status table
        $statuses = \App\Models\Status::pluck('name', 'id')->toArray();

        // Get inspections grouped by status
        $currentInspectionsByStatus = $this->inspections()
            ->whereMonth('created_at', $currentMonth->month)
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) use ($statuses) {
                // Map status_id to status name
                $statusName = $statuses[$item->status_id] ?? 'Unknown';
                return [$statusName => $item->total];
            });

        $previousInspectionsByStatus = $this->inspections()
            ->whereMonth('created_at', $lastMonth->month)
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) use ($statuses) {
                $statusName = $statuses[$item->status_id] ?? 'Unknown';
                return [$statusName => $item->total];
            });

        // Status distribution with names
        $statusDistribution = $this->inspections()
            ->whereYear('created_at', now()->year)
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) use ($statuses) {
                $statusName = $statuses[$item->status_id] ?? 'Unknown';
                return [$statusName => [
                    'total' => $item->total,
                    'percentage' => $this->calculatePercentage($item->total)
                ]];
            });

        return [
            'inspections_by_status' => [
                'current' => $currentInspectionsByStatus,
                'previous' => $previousInspectionsByStatus,
            ],
            'status_distribution' => $statusDistribution,
            'monthly_trend' => $this->getMonthlyTrend()
        ];
    }

    private function getStatusDistribution()
    {
        return $this->inspections()
            ->whereYear('created_at', now()->year)
            ->select('status_id', DB::raw('count(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status_id => [
                    'total' => $item->total,
                    'percentage' => $this->calculatePercentage($item->total)
                ]];
            });
    }

    private function getMonthlyTrend()
    {
        return $this->inspections()
            ->whereYear('created_at', now()->year)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                'status_id',
                DB::raw('count(*) as total')
            )
            ->groupBy('month', 'status_id')
            ->orderBy('month')
            ->get()
            ->groupBy('month');
    }

    private function calculatePercentage($statusCount)
    {
        $totalInspections = $this->inspections()
            ->whereYear('created_at', now()->year)
            ->count();

        return $totalInspections > 0
            ? round(($statusCount / $totalInspections) * 100, 2)
            : 0;
    }

    private function calculateEfficiencyRate()
    {
        $totalTasks = $this->tasks()
            ->whereMonth('created_at', now()->month)
            ->count();

        $completedTasks = $this->tasks()
            ->where('status', 'completed')
            ->whereMonth('completed_at', now()->month)
            ->count();

        return $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 2)
            : 0;
    }
}
