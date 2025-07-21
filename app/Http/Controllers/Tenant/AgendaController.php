<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Carbon\Carbon;

class AgendaController extends Controller
{
    public function index()
    {
        return view('app.tenant.agenda.index');
    }

    public function getEvents(Request $request)
    {
        // Get start and end dates from the request (if provided)
        $start = $request->input('start', Carbon::now()->subMonth()->format('Y-m-d'));
        $end = $request->input('end', Carbon::now()->addMonth()->format('Y-m-d'));

        // Fetch events from the database
        $events = Agenda::where(function ($query) use ($start, $end) {
            $query->whereBetween('start_date', [$start, $end])
                ->orWhereBetween('end_date', [$start, $end]);
        })
            ->get();

        // Format events for FullCalendar
        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_date,
                'end' => $event->end_date,
                'allDay' => $event->all_day ?? false,
                'color' => $event->color ?? '#3788d8',
            ];
        });

        return response()->json($formattedEvents);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'all_day' => 'boolean',
            'color' => 'nullable|string',
        ]);

        // Create new event
        $event = Agenda::create($validatedData);

        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }

    public function update(Request $request, Agenda $agenda)
    {
        // Validate the request
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'all_day' => 'sometimes|boolean',
            'color' => 'nullable|string',
        ]);

        // Update the event
        $agenda->update($validatedData);

        return response()->json([
            'success' => true,
            'event' => $agenda
        ]);
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
