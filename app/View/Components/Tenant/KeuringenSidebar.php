<?php

namespace App\View\Components\Tenant;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Employee;
use App\Models\Status;

class KeuringenSidebar extends Component
{
    public $inspection;
    public $isEdit;
    /**
     * Create a new component instance.
     */
    public function __construct($inspection = null)
    {
        $this->inspection = $inspection;
        $route = request()->route();
        $this->isEdit = $route && $route->action['as'] === 'tenant.inspections.edit';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $employes = Employee::all();
        $statuses = Status::all();
        $inspection = $this->inspection;
        return view('app.tenant.components.keuringen-sidebar', compact('employes', 'statuses', 'inspection'));
    }
}
