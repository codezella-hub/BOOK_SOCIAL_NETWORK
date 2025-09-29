@extends('layouts.admin-layout')

@section('title', 'Events')

@section('content')
<div class="admin-section">
    <div class="events-header">
        <h2 class="section-title">Events</h2>
        <a href="{{ route('admin.events.create') }}" class="btn-primary--pill">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" d="M12 5v14M5 12h14" />
            </svg>
            Create Event
        </a>
    </div>

    @if ($events->isEmpty())
    <div class="admin-section" style="text-align:center;">
        <svg class="icon" style="width:32px;height:32px;color:#9ca3af" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 0 0 2-2v-6H3v6a2 2 0 0 0 2 2z" />
        </svg>
        <p style="margin-top:8px;color:#6b7280">No events yet. Create the first one.</p>
    </div>
    @else
    <div class="events-grid">
        @foreach ($events as $event)
        <div class="event-card">
            <div class="event-card__media" @if($event->cover_image_path)
                style="background-image:url('{{ Storage::url($event->cover_image_path) }}')"
                @endif>
            </div>

            <div class="event-card__body">
                <div style="display:flex;justify-content:space-between;gap:10px">
                    <div style="min-width:0">
                        <h3 class="event-card__title">{{ $event->title }}</h3>
                        <div class="event-card__meta">
                            {{ $event->starts_at->toDayDateTimeString() }} • {{ $event->location_text ?? 'TBA' }}
                        </div>
                    </div>

                    <span class="event-card__badge
                {{ $event->status === 'published' ? 'badge--published' : ($event->status === 'draft' ? 'badge--draft' : 'badge--cancelled') }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="event-card__stats">
                    <span style="display:inline-flex;align-items:center;gap:6px">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="2" stroke-linecap="round" d="M17 20h5v-2a4 4 0 0 0-5-3.87M9 20H4v-2a4 4 0 0 1 5-3.87M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                        </svg>
                        @if(is_null($event->capacity)) Unlimited @else {{ $event->capacity_remaining }} left @endif
                    </span>
                    <span style="font-size:12px;color:#9ca3af">Starts {{ $event->starts_at->diffForHumans() }}</span>
                </div>

                <div class="event-card__toolbar">
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn-chip">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="2" stroke-linecap="round" d="M16 3l5 5-11 11H5V13L16 3z" />
                        </svg>
                        Edit
                    </a>

                    @if($event->status !== 'published')
                    <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                        @csrf
                        <button type="submit" class="icon-btn icon-btn--ok" title="Publish" aria-label="Publish">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </button>
                    </form>
                    @endif

                    @if($event->status !== 'cancelled')
                    <form method="POST" action="{{ route('admin.events.cancel', $event) }}">
                        @csrf
                        <button type="submit" class="icon-btn icon-btn--warn" title="Cancel" aria-label="Cancel">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="2" stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.events.participants', $event) }}" class="icon-btn" title="Participants" aria-label="Participants">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-width="2" stroke-linecap="round" d="M17 20h5v-2a4 4 0 0 0-5-3.87M9 20H4v-2a4 4 0 0 1 5-3.87M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:24px">
        {{ $events->onEachSide(1)->links() }}
    </div>
    @endif
</div>
@endsection
