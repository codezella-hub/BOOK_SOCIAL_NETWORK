<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;

class AdminParticipantController extends Controller
{
    public function index(Evenement $event)
    {
       // $this->authorize('manageParticipants', $event);

        $participants = $event->participants()
            ->withPivot(['status','ticket_id'])
            ->with('tickets')
            ->paginate(20);

        return view('admin.events.participants', compact('event','participants'));
    }
}
