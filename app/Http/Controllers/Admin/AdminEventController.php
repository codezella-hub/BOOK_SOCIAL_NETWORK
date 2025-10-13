<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEvenementRequest;
use App\Http\Requests\UpdateEvenementRequest;
use App\Models\Evenement;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index(Request $request)
    {
       // $this->authorize('viewAny', Evenement::class);

        $events = Evenement::query()
            ->with('organizer')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('from'), fn($q) => $q->where('starts_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn($q) => $q->where('starts_at', '<=', $request->date('to')))
            ->latest('starts_at')
            ->paginate(12)
            ->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(StoreEvenementRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $event = Evenement::create($data);

        return redirect()->route('admin.events.edit', $event)->with('status', 'Event created.');
    }

    public function edit(Evenement $event)
    {
       // $this->authorize('update', $event);
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEvenementRequest $request, Evenement $event)
    {
       // $this->authorize('update', $event);
        $event->update($request->validated());
        return back()->with('status', 'Event updated.');
    }

    public function publish(Evenement $event)
    {
       // $this->authorize('publish', $event);
        $event->update(['status' => 'published', 'published_at' => now()]);
        return back()->with('status', 'Event published.');
    }

    public function cancel(Evenement $event)
    {
       // $this->authorize('cancel', $event);
        $event->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        return back()->with('status', 'Event cancelled.');
    }
}
