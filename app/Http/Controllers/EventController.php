<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Evenement::query()
            ->published()
            ->visibleTo($request->user())
            ->with('organizer')
            ->withCount([
                'participants as going_count' => fn($q) => $q->where('status','going'),
                'participants as interested_count' => fn($q) => $q->where('status','interested'),
            ])
            ->when($request->filled('from'), fn($q) => $q->where('starts_at','>=',$request->date('from')))
            ->when($request->filled('to'), fn($q) => $q->where('starts_at','<=',$request->date('to')))
            ->orderBy('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events'));
    }

    public function show(Evenement $event)
    {
        $this->authorize('view', $event);

        $event->load('organizer');

        $userStatus = null;
        $ticket = null;
        if (auth()->check()) {
            $pivot = $event->participants()->where('user_id', auth()->id())->first()?->pivot;
            $userStatus = $pivot?->status;
            $ticketId = $pivot?->ticket_id;
            if ($ticketId) {
                $ticket = $event->tickets()->where('id', $ticketId)->first();
            }
        }

        $goingCount = $event->participants()->wherePivot('status','going')->count();
        $interestedCount = $event->participants()->wherePivot('status','interested')->count();

        return view('events.show', compact('event','userStatus','ticket','goingCount','interestedCount'));
    }
}
