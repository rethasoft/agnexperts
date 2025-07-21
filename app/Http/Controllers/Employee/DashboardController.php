<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Keuringen;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('app.employee.dashboard');
    }
}
