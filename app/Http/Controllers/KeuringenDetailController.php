<?php

namespace App\Http\Controllers;

use App\Models\KeuringenDetail;
use Illuminate\Http\Request;

class KeuringenDetailController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(KeuringenDetail $keuringenDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KeuringenDetail $keuringenDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KeuringenDetail $keuringenDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KeuringenDetail $keuringenDetail)
    {
        try {
            $keuringenDetail->delete();

            return response()->json(['msg' => 'Dienst succesvol verwijderd']);
        } catch (\Exception $e) {
            // Log or handle the exception as needed
            return response()->json(['msg' => 'Er is een fout opgetreden bij het verwijderen van de dienst.']);
        }
    }
}
