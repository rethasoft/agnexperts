<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Keuringen;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $clientId = auth()->user()->client->id;
        $now = Carbon::now();

        // Geçen ay ve bu yıl için sorgular
        $lastMonthCount = Keuringen::where('client_id', $clientId)
            ->whereYear('created_at', $now->copy()->subMonth()->year)
            ->whereMonth('created_at', $now->copy()->subMonth()->month)
            ->count();

        $thisYearCount = Keuringen::where('client_id', $clientId)
            ->whereYear('created_at', $now->year)
            ->count();

        $statistics = [
            'total_keuringen' => Keuringen::where('client_id', $clientId)->count(),

            // Durumlara göre sayılar
            'status_counts' => Keuringen::where('client_id', $clientId)
                ->with('getStatus')
                ->get()
                ->groupBy('getStatus.name')
                ->map(function ($group) {
                    return $group->count();
                }),

            // Son 5 keuring
            'recent_keuringen' => Keuringen::where('client_id', $clientId)
                ->latest()
                ->take(5)
                ->get(),

            // Bu ayki toplam
            'this_month_count' => Keuringen::where('client_id', $clientId)
                ->whereMonth('created_at', now()->month)
                ->count(),

            // Yeni eklenen sorgular
            'last_month_count' => $lastMonthCount,
            'this_year_count' => $thisYearCount,
        ];

        return view('app.client.dashboard', compact('statistics'));
    }
}
