<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Http\Requests\RSVPRequest;
use App\Models\Evenement;
use App\Models\Ticket;
use App\Services\TicketPdfService;
use Illuminate\Http\RedirectResponse;

class RSVPController extends Controller
{
    public function store(RSVPRequest $request, Evenement $event, TicketPdfService $pdf): RedirectResponse
    {
    Log::info('RSVP attempt', [
            'event_id' => $event->id,
            'event_slug' => $event->slug,
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'status' => $request->string('status'),
            'url' => $request->url(),
        ]);

    $this->authorize('rsvp', $event);

        $user = $request->user();
    $status = (string) $request->input('status');

        // capacity check for "going"
        if ($status === 'going' && !is_null($event->capacity)) {
            $goingCount = $event->participants()->wherePivot('status','going')->count();
            if ($goingCount >= $event->capacity) {
                return back()->withErrors(['status' => 'Capacity reached for this event.']);
            }
        }

        // Ensure user is attached to event (creates pivot if missing)
        if (!$event->participants()->where('user_id', $user->id)->exists()) {
            $event->participants()->attach($user->id, ['status' => 'uninterested', 'ticket_id' => null]);
        }
        $pivot = $event->participants()->where('user_id', $user->id)->first();
        $currentTicketId = $pivot?->pivot?->ticket_id;

        if ($status === 'going') {
            // Ensure ticket exists
            if (!$currentTicketId) {
                $ticket = Ticket::where('evenement_id', $event->id)
                    ->where('user_id', $user->id)
                    ->first();
                if (!$ticket) {
                    $ticket = new Ticket([
                        'evenement_id' => $event->id,
                        'user_id' => $user->id,
                        'code' => $pdf->makeCode(),
                        'issued_at' => now(),
                    ]);
                    $ticket->save();
                    $path = $pdf->generateAndStore($ticket);
                    $ticket->update(['pdf_path' => $path]);
                }
                $currentTicketId = $ticket->id;
            }
            // Always update both status and ticket_id
            $event->participants()->updateExistingPivot($user->id, [
                'status' => 'going',
                'ticket_id' => $currentTicketId
            ]);
            Log::info('RSVP after update', [
                'status' => $status,
                'request_status' => $request->input('status'),
                'user_id' => $user->id,
                'event_id' => $event->id,
                'pivot' => $event->participants()->get()->map(function($u) { return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'pivot_status' => $u->pivot->status ?? null,
                    'pivot_ticket_id' => $u->pivot->ticket_id ?? null,
                ]; })->toArray()
            ]);
        } elseif ($status === 'interested') {
            $event->participants()->updateExistingPivot($user->id, [
                'status' => 'interested',
                'ticket_id' => $currentTicketId
            ]);
            Log::info('RSVP after update', [
                'status' => $status,
                'request_status' => $request->input('status'),
                'user_id' => $user->id,
                'event_id' => $event->id,
                'pivot' => $event->participants()->get()->map(function($u) { return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'pivot_status' => $u->pivot->status ?? null,
                    'pivot_ticket_id' => $u->pivot->ticket_id ?? null,
                ]; })->toArray()
            ]);
        } else { // uninterested
            // keep ticket record but detach from pivot
            $event->participants()->updateExistingPivot($user->id, [
                'status' => 'uninterested',
                'ticket_id' => null
            ]);
            Log::info('RSVP after update', [
                'status' => $status,
                'request_status' => $request->input('status'),
                'user_id' => $user->id,
                'event_id' => $event->id,
                'pivot' => $event->participants()->get()->map(function($u) { return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'pivot_status' => $u->pivot->status ?? null,
                    'pivot_ticket_id' => $u->pivot->ticket_id ?? null,
                ]; })->toArray()
            ]);
        }

        return back()->with('status', 'Your response has been recorded.');
    }
}
