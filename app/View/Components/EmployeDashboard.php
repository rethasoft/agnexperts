<?php

namespace App\View\Components;

use App\Models\Keuringen;
use App\Models\Status;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class EmployeDashboard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $employee_id = Auth::guard('employee')->id();
        $totalKeuringen = Keuringen::with(['event' => function($query) use($employee_id) {
            $query->where('employee_id', $employee_id);
        }])
        ->selectRaw('count(*) as count, status')
        ->groupBy('status')
        ->get();


        $getTotal = Keuringen::with(['event' => function($query) use($employee_id) { $query->where('employee_id', $employee_id); }]) ->count();

        $statuses = [];
        foreach ($totalKeuringen as $keuring) {
            $status = Status::find($keuring->status);
            if ($status) {
                $statuses[$status->name] = [
                    'count' => $keuring->count,
                    'color' => $status->color
                ];
            }
        }
        return view('components.employe-dashboard', compact('statuses', 'getTotal'));
    }
}
