@php
    // $userStatus may be 'going', 'interested', 'uninterested', or null
@endphp
<div id="rsvpV2" class="rsvp-wrap">
  <div class="rsvp-grid" role="group" aria-label="RSVP actions">
    <form method="POST" action="{{ route('events.rsvp', $event) }}" class="rsvp-col">
      @csrf
      <input type="hidden" name="status" value="going">
      <button type="submit"
              class="rsvp-tile {{ $userStatus==='going' ? 'is-active is-going' : 'is-going' }}"
              aria-pressed="{{ $userStatus==='going' ? 'true' : 'false' }}">
        <span class="tile-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="tile-svg"><path d="M2 12l5 5L22 4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <span class="tile-text">
          <span class="tile-title">I’m Going</span>
          <span class="tile-sub">Reserve a seat</span>
        </span>
      </button>
    </form>

    <form method="POST" action="{{ route('events.rsvp', $event) }}" class="rsvp-col">
      @csrf
      <input type="hidden" name="status" value="interested">
      <button type="submit"
              class="rsvp-tile {{ $userStatus==='interested' ? 'is-active is-interest' : 'is-interest' }}"
              aria-pressed="{{ $userStatus==='interested' ? 'true' : 'false' }}">
        <span class="tile-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="tile-svg"><path d="M12 3v18M3 12h18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <span class="tile-text">
          <span class="tile-title">Interested</span>
          <span class="tile-sub">Get updates</span>
        </span>
      </button>
    </form>

    <form method="POST" action="{{ route('events.rsvp', $event) }}" class="rsvp-col">
      @csrf
      <input type="hidden" name="status" value="uninterested">
      <button type="submit"
              class="rsvp-tile {{ $userStatus==='uninterested' ? 'is-active is-skip' : 'is-skip' }}"
              aria-pressed="{{ $userStatus==='uninterested' ? 'true' : 'false' }}">
        <span class="tile-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="tile-svg"><path d="M4 4l16 16M20 4L4 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <span class="tile-text">
          <span class="tile-title">Skip</span>
          <span class="tile-sub">Not this one</span>
        </span>
      </button>
    </form>

    @if($userStatus === 'going' && $ticket)
      <a href="{{ route('tickets.download', [$event, $ticket]) }}" class="rsvp-tile ticket-tile" title="Download ticket">
        <span class="tile-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" class="tile-svg"><path d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        </span>
        <span class="tile-text">
          <span class="tile-title">Download Ticket</span>
          <span class="tile-sub">PDF / Pass</span>
        </span>
      </a>
    @endif
  </div>

  @if ($errors->any())
    <div class="rsvp-note err" role="alert">{{ $errors->first() }}</div>
  @endif
  @if (session('status'))
    <div class="rsvp-note ok" role="status">{{ session('status') }}</div>
  @endif
</div>


<style>
/* Scoped to the unique wrapper so it wins the cascade */
#rsvpV2.rsvp-wrap { margin-top: 1.2rem; }

/* Spread tiles across the card */
#rsvpV2 .rsvp-grid{
  display:flex !important;
  justify-content: space-between !important;
  align-items: stretch;
  gap: 1.25rem !important;
  flex-wrap: wrap;
}
#rsvpV2 .rsvp-col{ display: contents; }

/* Reset any .btn styles and render as big tiles */
#rsvpV2 .rsvp-tile{
  all: unset;
  display:flex; align-items:center; gap:1rem;
  box-sizing: border-box;
  width: clamp(220px, 24%, 360px);
  padding: 1rem 1rem;
  border-radius: 16px;
  border:1px solid transparent;
  background:#ffffff;
  text-decoration:none; cursor:pointer;
  box-shadow: 0 8px 20px rgba(5,7,9,.06);
  transition: transform .06s ease, box-shadow .2s ease, background .2s ease, border-color .2s ease, color .2s ease !important;
}
#rsvpV2 .rsvp-tile:hover{ transform: translateY(-3px); box-shadow: 0 14px 28px rgba(5,7,9,.10); }

@media (max-width: 1024px){
  #rsvpV2 .rsvp-tile{ width: calc(50% - .65rem); }
}
@media (max-width: 560px){
  #rsvpV2 .rsvp-tile{ width: 100%; }
}

/* Icon bubble */
#rsvpV2 .tile-icon{
  width:46px; height:46px; border-radius:14px;
  display:inline-flex; align-items:center; justify-content:center;
  background:#f3f4f6; color:#374151;
  flex: 0 0 auto;
}
#rsvpV2 .tile-svg{ width:22px; height:22px; }

/* Text */
#rsvpV2 .tile-text{ display:flex; flex-direction:column; line-height:1.2; }
#rsvpV2 .tile-title{ font-weight:900; color:#0f172a; font-size:1rem; letter-spacing:.2px; }
#rsvpV2 .tile-sub{ font-size:.82rem; color:#6b7280; }

/* Variants */
#rsvpV2 .is-going{ background:#dcfce7 !important; border-color:#86efac !important; }
#rsvpV2 .is-going .tile-icon{ background:#059669; color:#ffffff; }
#rsvpV2 .is-going .tile-title{ color:#065f46; }

#rsvpV2 .is-interest{ background:#e0e7ff !important; border-color:#a5b4fc !important; }
#rsvpV2 .is-interest .tile-icon{ background:#4f46e5; color:#ffffff; }
#rsvpV2 .is-interest .tile-title{ color:#3730a3; }

#rsvpV2 .is-skip{ background:#ffedd5 !important; border-color:#fdba74 !important; }
#rsvpV2 .is-skip .tile-icon{ background:#ea580c; color:#ffffff; }
#rsvpV2 .is-skip .tile-title{ color:#9a3412; }

#rsvpV2 .ticket-tile{ background:#0b1220 !important; border-color:#0b1220 !important; color:#fff !important; }
#rsvpV2 .ticket-tile .tile-icon{ background:#111827; color:#fff; }
#rsvpV2 .ticket-tile .tile-title{ color:#ffffff; }
#rsvpV2 .ticket-tile .tile-sub{ color:#d1d5db; }

/* Active emphasis */
#rsvpV2 .rsvp-tile.is-active{
  outline: 2px solid rgba(17,24,39,.1);
  box-shadow: 0 16px 32px rgba(17,24,39,.20);
}

/* Notes */
#rsvpV2 .rsvp-note{
  margin-top:.75rem; display:inline-block; padding:.45rem .7rem; border-radius:10px; font-size:.92rem;
}
#rsvpV2 .rsvp-note.err{ background:#fef2f2; color:#b91c1c; border:1px solid #fee2e2; }
#rsvpV2 .rsvp-note.ok{ background:#ecfdf5; color:#065f46; border:1px solid #d1fae5; }

/* Final override for stubborn global .btn rules */
#rsvpV2 .rsvp-tile,
#rsvpV2 .rsvp-tile *{
  font-family: inherit !important;
  background-clip: initial !important;
}

</style>
