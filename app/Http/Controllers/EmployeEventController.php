<?php

namespace App\Http\Controllers;

use App\Models\EmployeEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;  // Import the Log facade

class EmployeEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {


            $event = new EmployeEvent();
            $event->keuringen_id = 0;
            $event->title = $request->title;
            $event->employee_id = $request->employee_id;
            $event->start = $request->start_date;
            $event->end = $request->end_date;
            $saved_event = $event->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Evenement succesvol aangemaakt',
                'data' => $saved_event
            ], 201);

            return response()->json([
                'status' => 'success',
                'message' => 'Evenement succesvol aangemaakt'
            ], 201);
        } catch (\Throwable $th) {
            Log::error('Employe Event Store: ' . $th->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Evenement kan niet worden gemaakt'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeEvent $employeEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeEvent $employeEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeEvent $employeEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeEvent $employeEvent)
    {
        return $employeEvent;
    }
}
