@extends('layouts.user-layout')
@section('title', $event->title)

@section('content')
<section class="event-page">
  {{-- Wide Featured Card --}}
  <article class="event-feature">
    @if($event->cover_image_path)
      <img src="{{ asset('storage/'.$event->cover_image_path) }}" alt="{{ $event->title }}" class="feature-media">
    @else
      <div class="feature-media feature-media--placeholder"></div>
    @endif

    <div class="feature-body">
      <h1 class="feature-title">{{ $event->title }}</h1>

      <div class="feature-meta">
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

        <span class="meta-line">
          <svg viewBox="0 0 24 24" class="icon"><path d="M5 12h14M5 12a7 7 0 1 1 14 0" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/></svg>
          {{ ucfirst($event->visibility) }}
        </span>
      </div>

      @if($event->summary)
        <p class="feature-summary">{{ $event->summary }}</p>
      @endif

      @if($event->description)
        <div class="feature-desc">{!! nl2br(e($event->description)) !!}</div>
      @endif

      <div class="feature-stats">
        <span><strong>Going:</strong> {{ $goingCount }}</span>
        <span><strong>Interested:</strong> {{ $interestedCount }}</span>
        @if(!is_null($event->capacity))
          <span><strong>Remaining:</strong> {{ $event->capacity_remaining }}</span>
        @endif
      </div>

      <div class="feature-actions">
        @auth
          @include('events.partials._rsvp', ['event'=>$event, 'userStatus'=>$userStatus, 'ticket'=>$ticket])
        @else
          <a class="cta" href="{{ route('login') }}">Log in to RSVP</a>
        @endauth
      </div>
    </div>
  </article>

  {{-- Smaller Cards: More Events --}}
  <div class="more-block">
    <h2 class="more-title">Other Events You May Like</h2>

    @php
      $otherEvents = \App\Models\Evenement::query()
        ->published()
        ->where('visibility','public')
        ->where('id','!=',$event->id)
        ->orderBy('starts_at')
        ->take(4)
        ->get();
    @endphp

    @if($otherEvents->isEmpty())
      <p class="more-empty">No other events found.</p>
    @else
      <div class="more-grid">
        @foreach($otherEvents as $other)
          <article class="mini-card">
            @if($other->cover_image_path)
              <img src="{{ asset('storage/'.$other->cover_image_path) }}" alt="{{ $other->title }}" class="mini-media">
            @else
              <div class="mini-media mini-media--placeholder"></div>
            @endif

            <div class="mini-body">
              <h3 class="mini-title" title="{{ $other->title }}">{{ $other->title }}</h3>
              <p class="mini-meta">{{ $other->starts_at->toDayDateTimeString() }}</p>
              <a href="{{ route('events.show', $other->slug) }}" class="mini-cta">View details</a>
            </div>
          </article>
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- Scoped styles --}}
<style>
  .event-page { width: 100%; max-width: 64rem; margin: 2.5rem auto; padding: 0 1rem; }

  /* Featured wide card */
  .event-feature {
    background: #fff; border: 1px solid #eaeaea; border-radius: 16px;
    box-shadow: 0 10px 28px rgba(5,7,9,0.06); overflow: hidden;
  }
  .feature-media { width: 100%; height: 340px; object-fit: cover; display: block; }
  .feature-media--placeholder { background: linear-gradient(145deg,#eef2ff,#fafafa); height: 340px; }
  .feature-body { padding: 1.25rem 1.25rem 1.5rem; }
  .feature-title { margin: 0 0 .5rem; font-size: 1.875rem; font-weight: 800; color: #0f172a; }
  .feature-meta { display: flex; flex-wrap: wrap; gap: .75rem 1rem; color: #6b7280; font-size: .95rem; margin-bottom: .75rem; }
  .meta-line { display: inline-flex; align-items: center; gap: .5rem; }
  .icon { width: 18px; height: 18px; color: #6b7280; }
  .feature-summary { margin: .5rem 0 0; font-size: 1.05rem; color: #374151; }
  .feature-desc { margin-top: .75rem; color: #111827; line-height: 1.6; }
  .feature-stats { margin-top: 1rem; display: flex; gap: 1.25rem; color: #374151; }
  .feature-actions { margin-top: 1rem; }
  .cta { display: inline-block; background: #1b1b18; color: #fff; padding: .55rem .9rem; border-radius: .4rem; text-decoration: none; font-weight: 600; }
  .cta:hover { background: #000; }

  /* More events section */
  .more-block { margin-top: 2.5rem; }
  .more-title { font-size: 1.375rem; font-weight: 700; margin-bottom: .75rem; color: #0f172a; }
  .more-empty { color: #6b7280; text-align: center; margin-top: .5rem; }
  .more-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }

  .mini-card {
    display: flex; flex-direction: column; background: #fff; border: 1px solid #eaeaea;
    border-radius: 12px; overflow: hidden; box-shadow: 0 6px 18px rgba(5,7,9,0.05);
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .mini-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(5,7,9,0.08); }
  .mini-media { width: 100%; height: 120px; object-fit: cover; display: block; }
  .mini-media--placeholder { background: linear-gradient(145deg,#eef2ff,#fafafa); height: 120px; }
  .mini-body { padding: .8rem .9rem 1rem; display: flex; flex-direction: column; gap: .4rem; }
  .mini-title { margin: 0; font-size: 1rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .mini-meta { font-size: .85rem; color: #6b7280; }
  .mini-cta { margin-top: .35rem; align-self: start; background: #1b1b18; color: #fff; padding: .4rem .65rem; border-radius: .35rem; text-decoration: none; font-size: .85rem; font-weight: 600; }
  .mini-cta:hover { background: #000; }

  @media (max-width: 640px) {
    .feature-title { font-size: 1.5rem; }
    .feature-media, .feature-media--placeholder { height: 240px; }
  }
</style>
@endsection
