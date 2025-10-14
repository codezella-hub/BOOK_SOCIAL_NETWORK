@extends('layouts.user-layout')
@section('title', $event->title)

@section('content')
<section class="event-page">




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

  /* Metrics row */
  .event-metrics{
    display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; margin-top:.75rem;
    padding: .25rem 0;
  }
  .metric{
    display:inline-flex; align-items:center; gap:.45rem;
    background:#f8fafc; border:1px solid #e5e7eb; color:#374151;
    padding:.35rem .6rem; border-radius:999px; font-size:.875rem; font-weight:700;
  }
  .metric .m-label{ font-weight:600; }
/* Compact number badge for metrics */
.metric .m-val{
  background:#fff;
  border:1px solid #e5e7eb;
  min-width: 1.5rem;
  height: 1.5rem;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border-radius:999px;
  padding:0 .25rem;
  font-size:.8rem;
  line-height:1;
}
.metric{ background:#fafafa; }
.metric.ok{ background:#f0fdf4; }    /* even lighter green */
.metric.info{ background:#f5f7ff; }  /* even lighter indigo */
.metric.cap{ background:#fffaf0; }   /* lighter amber */

  .m-ico{ width:16px; height:16px; }

  .metric.ok{ background:#ecfdf5; border-color:#d1fae5; color:#047857; }
  .metric.info{ background:#eef2ff; border-color:#c7d2fe; color:#3730a3; }
  .metric.cap{ background:#fff7ed; border-color:#fed7aa; color:#9a3412; }
  .metric.cap.is-low{ background:#fef2f2; border-color:#fecaca; color:#b91c1c; }

  /* RSVP */
  .feature-actions { margin-top: 1rem; }
  .rsvp-wrap { display:grid; gap:.5rem; }
  .rsvp-bar { display:flex; flex-wrap:wrap; gap:.5rem; align-items:center; }
  .rsvp-btn{
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.55rem .9rem; border-radius:999px; border:1px solid #e5e7eb;
    background:#fff; color:#111827; font-weight:700; font-size:.9rem;
    transition: transform .05s ease, background .2s, border-color .2s, color .2s, box-shadow .2s;
    cursor:pointer; text-decoration:none;
  }
  .rsvp-btn:hover{ transform: translateY(-1px); }
  .rsvp-btn.is-active{ box-shadow:0 6px 16px rgba(5,7,9,.08); }

  .rsvp-btn.ok{ background:#ecfdf5; border-color:#d1fae5; color:#047857; }
  .rsvp-btn.ok:hover{ background:#d1fae5; }
  .rsvp-btn.info{ background:#eef2ff; border-color:#c7d2fe; color:#3730a3; }
  .rsvp-btn.info:hover{ background:#e0e7ff; }
  .rsvp-btn.muted{ background:#f3f4f6; border-color:#e5e7eb; color:#374151; }
  .rsvp-btn.muted:hover{ background:#e5e7eb; }
  .rsvp-btn.dark{ background:#1b1b18; border-color:#1b1b18; color:#fff; }
  .rsvp-btn.dark:hover{ background:#000; }
  .ico{ width:16px; height:16px; }

  .rsvp-msg{ font-size:.9rem; padding:.35rem .6rem; border-radius:.5rem; display:inline-block; }
  .rsvp-msg.err{ background:#fef2f2; color:#b91c1c; border:1px solid #fee2e2; }
  .rsvp-msg.ok-msg{ background:#ecfdf5; color:#047857; border:1px solid #d1fae5; }

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

      {{-- Modern metrics --}}
      @php
        $lowRemaining = !is_null($event->capacity) && $event->capacity_remaining <= 10;
      @endphp
      <ul class="event-metrics" role="list">
        <li class="metric ok" title="Going">
          <svg class="m-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
          </svg>
          <span class="m-label">Going</span>
          <span class="m-val">{{ $goingCount }}</span>
        </li>

        <li class="metric info" title="Interested">
          <svg class="m-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-width="2" stroke-linecap="round" d="M12 8v4m0 4h.01"/>
            <circle cx="12" cy="12" r="9" stroke-width="2"/>
          </svg>
          <span class="m-label">Interested</span>
          <span class="m-val">{{ $interestedCount }}</span>
        </li>

        @if(!is_null($event->capacity))
          <li class="metric cap {{ $lowRemaining ? 'is-low' : '' }}" title="Remaining seats">
            <svg class="m-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-width="2" stroke-linecap="round" d="M3 7h18M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2m-1 5v6a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-6h10z"/>
            </svg>
            <span class="m-label">Remaining</span>
            <span class="m-val">{{ $event->capacity_remaining }}</span>
          </li>
        @endif
      </ul>

      {{-- RSVP actions --}}
      <div class="feature-actions">
        @auth
          @php
            // $userStatus: 'going'|'interested'|'uninterested'|null
          @endphp
          <div class="rsvp-wrap">
            <div class="rsvp-bar" role="group" aria-label="RSVP actions">
              <form method="POST" action="{{ route('events.rsvp', $event) }}">
                @csrf
                <input type="hidden" name="status" value="going">
                <button type="submit"
                        class="rsvp-btn {{ $userStatus==='going' ? 'is-active ok' : 'ok' }}"
                        aria-pressed="{{ $userStatus==='going' ? 'true' : 'false' }}">
                  <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" d="M5 13l4 4L19 7"/>
                  </svg>
                  I’m Going
                </button>
              </form>

              <form method="POST" action="{{ route('events.rsvp', $event) }}">
                @csrf
                <input type="hidden" name="status" value="interested">
                <button type="submit"
                        class="rsvp-btn {{ $userStatus==='interested' ? 'is-active info' : 'info' }}"
                        aria-pressed="{{ $userStatus==='interested' ? 'true' : 'false' }}">
                  <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" d="M12 8v4m0 4h.01"/>
                    <circle cx="12" cy="12" r="9" stroke-width="2"/>
                  </svg>
                  Interested
                </button>
              </form>

              <form method="POST" action="{{ route('events.rsvp', $event) }}">
                @csrf
                <input type="hidden" name="status" value="uninterested">
                <button type="submit"
                        class="rsvp-btn {{ $userStatus==='uninterested' ? 'is-active muted' : 'muted' }}"
                        aria-pressed="{{ $userStatus==='uninterested' ? 'true' : 'false' }}">
                  <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  Uninterested
                </button>
              </form>

              @if($userStatus === 'going' && $ticket)
                <a href="{{ route('tickets.download', [$event, $ticket]) }}" class="rsvp-btn dark" title="Download ticket">
                  <svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-width="2" stroke-linecap="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/>
                  </svg>
                  Download Ticket
                </a>
              @endif
            </div>

            @if ($errors->any())
              <div class="rsvp-msg err" role="alert">{{ $errors->first() }}</div>
            @endif
            @if (session('status'))
              <div class="rsvp-msg ok-msg" role="status">{{ session('status') }}</div>
            @endif
          </div>
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


@endsection
