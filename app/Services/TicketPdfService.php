<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;

class TicketPdfService
{
    // Placeholder: render HTML and store as .html; in production, swap to dompdf/snappy to output PDF
    public function generateAndStore(Ticket $ticket): string
    {
        $html = View::make('tickets.pdf', ['ticket' => $ticket])->render();
        $filename = 'tickets/'.$ticket->code.'.html'; // swap to .pdf when integrating real PDF lib
        Storage::disk('public')->put($filename, $html);
        return $filename;
    }

    public function makeCode(): string
    {
        return strtoupper(Str::random(10));
    }
}
