<?php

namespace App\Http\Controllers;

use App\Models\{Agenda, Client, Type, Employee};
use App\Domain\Inspections\Models\Inspection;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.agenda.';
    }

    public function index()
    {
        $clients = Client::all();
        $inspectionTypes = Type::all();
        $employees = Employee::all();
        $inspections = Inspection::all();
        return view($this->path . 'list', compact('clients', 'inspectionTypes', 'employees', 'inspections'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Agenda $agenda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Agenda $agenda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        //
    }
}
