<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Domain\Inspections\Models\Inspection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = auth()->user()->id;
        $now = Carbon::now();

        // Geçen ay ve bu yıl için sorgular
        $lastMonthCount = Inspection::where('client_id', $clientId)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();

        $thisYearCount = Inspection::where('client_id', $clientId)
            ->whereYear('created_at', $now->year)
            ->count();

        $statistics = [
            'total_keuringen' => Inspection::where('client_id', $clientId)->count(),

            // Durumlara göre sayılar
            'status_counts' => Inspection::where('client_id', $clientId)
                ->with('status')
                ->get()
                ->groupBy('status.name')
                ->map(function ($group) {
                    return $group->count();
                }),

            // Son 5 keuring
            'recent_keuringen' => Inspection::where('client_id', $clientId)
                ->latest()
                ->take(5)
                ->get(),

            // Bu ayki toplam
            'this_month_count' => Inspection::where('client_id', $clientId)
                ->whereMonth('created_at', now()->month)
                ->count(),

            // Yeni eklenen sorgular
            'last_month_count' => $lastMonthCount,
            'this_year_count' => $thisYearCount,
        ];

        return view('app.client.dashboard', compact('statistics'));
    }
}
