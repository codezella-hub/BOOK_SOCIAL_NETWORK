<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;

class TicketDownloadController extends Controller
{
    public function __invoke(Evenement $event, Ticket $ticket)
    {
        // Ensure the ticket belongs to the event
        abort_unless($ticket->evenement_id === $event->id, 404);

        // Optional: authorization via policy (owner or organizer)
        $this->authorize('downloadTicket', $ticket);

        // Fallback MIME based on extension
        $path = $ticket->pdf_path;
        abort_if(!$path || !Storage::disk('public')->exists($path), 404, 'Ticket file not found.');

        $isPdf = str_ends_with(strtolower($path), '.pdf');
        $mime = $isPdf ? 'application/pdf' : 'text/html';
        $ext = $isPdf ? 'pdf' : 'html';

        return Storage::disk('public')->download($path, "ticket-{$ticket->code}.{$ext}", [
            'Content-Type' => $mime,
        ]);
    }
}
