<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domain\Inspections\Models\Inspection;
use App\Models\Client;
use App\Models\Status;

class DashboardController extends Controller
{
    public function index()
    {
        $inspections = Inspection::orderByDesc('id')->limit(10)->get();
        $clients = Client::orderByDesc('id')->limit(10)->get();


        $totalClients = Client::count();
        $totalInspections = Inspection::count();

        $inspection = new Inspection();
        $totalIncome = $inspection->totalIncome();


        $getAllInspections = Inspection::selectRaw('count(*) as count, status_id')->groupBy('status_id')->get();
        $statuses = [];
        foreach ($getAllInspections as $inspection) {
            $status = Status::find($inspection->status_id);
            if ($status) {
                $statuses[$status->name] = [
                    'count' => $inspection->count,
                    'color' => $status->color
                ];
            }
        }


        return view('app.tenant.dashboard', compact('inspections', 'clients', 'totalClients', 'totalInspections', 'totalIncome', 'statuses'));
    }
}
