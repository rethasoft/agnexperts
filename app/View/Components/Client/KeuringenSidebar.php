<?php

namespace App\View\Components\Client;

use Illuminate\View\Component;
use App\Models\Inspection;
use Illuminate\Support\Facades\Log;

class KeuringenSidebar extends Component
{
    public $isEdit;
    public $inspection;
    public $files;

    /**
     * Create a new component instance.
     */
    public function __construct($inspection = null)
    {
        // Route kontrolünü daha detaylı yapalım
        $route = request()->route();
        $this->isEdit = $route && $route->action['as'] === 'client.inspections.edit';
        $this->inspection = $inspection;
        
        if ($this->isEdit && $inspection) {
            $this->files = $inspection->files; // Eğer files ilişkisi varsa
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('app.client.components.keuringen-sidebar', [
            'isEdit' => $this->isEdit,
            'inspection' => $this->inspection,
            'files' => $this->files
        ]);
    }
}
