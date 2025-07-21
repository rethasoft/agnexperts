<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
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
            $data = $request->note;
            $saved = Note::create($data);
            if (!$saved){
                return back()->withErrors(['msg' => 'De notitie kan niet worden gemaakt.']);
            }
            return back()->with('msg', 'Notitie is succesvol aangemaakt');
        } catch (\Throwable $th) {
            Log::error('Note store: ' . $th->getMessage());
            return back()->withErrors(['msg' => 'De notitie kan niet worden gemaakt.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return back()->with('msg', 'Bericht succesvol verwijderd');
    }
}
