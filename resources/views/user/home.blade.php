{{-- resources/views/home.blade.php --}}
@extends('layouts.user-layout')

@section('title', 'Accueil - Social Book Network')

@section('content')

@include('partials.hero-user')
@include('partials.categorie-user')
<section id="events">
  @include('partials.event-user')
</section>

<!-- Events Section -->
<!-- Events Section -->
{{-- <section id="events" class="events-wrap">
  <div class="events-head">
    <h2>Events</h2>
    <a href="{{ route('events.index') }}" class="pill-btn">View all</a>
  </div>

  @php
    $events = \App\Models\Evenement::query()
      ->published()
      ->where('visibility','public')
      ->orderBy('starts_at')
      ->take(6)
      ->get();
  @endphp

  @if($events->isEmpty())
    <p class="events-empty">No public events available.</p>
  @else
    <div class="events-grid">
      @foreach($events as $event)
        <article class="event-card">
          @if($event->cover_image_path)
            <img class="event-card__media" src="{{ Storage::url($event->cover_image_path) }}" alt="{{ $event->title }}">
          @else
            <div class="event-card__media event-card__media--placeholder"></div>
          @endif

          <div class="event-card__body">
            <h3 class="event-card__title" title="{{ $event->title }}">{{ $event->title }}</h3>

            <div class="event-card__meta">
              <span class="meta-line">
                <svg viewBox="0 0 24 24" class="icon"><path d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 0 0 2-2v-6H3v6a2 2 0 0 0 2 2z" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                {{ $event->starts_at->toDayDateTimeString() }}
              </span>
              @if($event->location_text)
                <span class="meta-line">
                  <svg viewBox="0 0 24 24" class="icon"><path d="M12 21s8-4.5 8-11a8 8 0 1 0-16 0c0 6.5 8 11 8 11zM12 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
                  {{ $event->location_text }}
                </span>
              @endif
            </div>

            @if($event->summary)
              <p class="event-card__summary">{{ $event->summary }}</p>
            @endif

            <div class="event-card__stats">
              <span class="stat">{{ is_null($event->capacity) ? 'Unlimited' : $event->capacity_remaining.' left' }}</span>
              <span class="hint">Starts {{ $event->starts_at->diffForHumans() }}</span>
            </div>

            <a href="{{ route('events.show', $event->slug) }}" class="cta">View details</a>
          </div>
        </article>
      @endforeach
    </div>
  @endif
</section> --}}

{{-- Local styles for Events --}}
<style>
  .events-wrap { width: 100%; max-width: 64rem; margin: 2.5rem auto; padding: 0 1rem; }
  .events-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
  .events-head h2 { margin: 0; font-size: 1.5rem; font-weight: 700; color: #0f172a; }
  .pill-btn { display: inline-flex; align-items: center; gap: .5rem; background: #1b1b18; color: #fff; padding: .5rem .75rem; border-radius: .375rem; font-size: .875rem; text-decoration: none; }
  .pill-btn:hover { background: #000; }

  .events-empty { margin-top: 1rem; text-align: center; color: #6b7280; }

  .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem; }

  .event-card { display: flex; flex-direction: column; background: #fff; border: 1px solid #eaeaea; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 16px rgba(5,7,9,0.05); transition: transform .2s ease, box-shadow .2s ease; }
  .event-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(5,7,9,0.08); }

  .event-card__media { width: 100%; height: 160px; object-fit: cover; display: block; }
  .event-card__media--placeholder { background: linear-gradient(145deg, #eef2ff, #fafafa); }

  .event-card__body { display: flex; flex-direction: column; gap: .5rem; padding: 1rem; }
  .event-card__title { margin: 0; font-size: 1.0625rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .event-card__meta { display: grid; gap: .25rem; color: #6b7280; font-size: .875rem; }
  .meta-line { display: inline-flex; align-items: center; gap: .5rem; }
  .icon { width: 16px; height: 16px; color: #6b7280; flex: 0 0 auto; }

  .event-card__summary { margin: .25rem 0 0; color: #374151; font-size: .9375rem; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

  .event-card__stats { display: flex; align-items: center; justify-content: space-between; color: #6b7280; font-size: .8125rem; margin-top: .25rem; }
  .stat { font-weight: 600; color: #111827; }
  .hint { color: #9ca3af; }

  .cta { margin-top: .5rem; display: inline-block; text-align: center; background: #1b1b18; color: #fff; padding: .5rem .75rem; border-radius: .375rem; text-decoration: none; font-weight: 600; }
  .cta:hover { background: #000; }
</style>


@include('partials.features-user')
@include('partials.community-user')
@include('partials.testimonials-user')
@include('partials.newsletter-user')
@endsection


