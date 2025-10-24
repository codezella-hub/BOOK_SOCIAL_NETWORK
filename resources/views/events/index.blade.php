@extends('layouts.user-layout')

<style>
/* Tabs */
#events-tabs .tabs{
  position: relative;
  display: grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 16px rgba(5,7,9,.05);
}
#events-tabs .tab{
  appearance:none;
  display:flex; align-items:center; justify-content:center;
  padding:.8rem .6rem;
  font-weight:700; color:#374151;
  background: transparent; border: none; cursor: pointer;
  position: relative; z-index: 1;
}
#events-tabs .tab:hover{ background:#f9fafb; }
#events-tabs .tab.is-active{ color:#111827; }
#events-tabs .tab-underline{
  position:absolute; bottom:0; left:0;
  width: calc(100% / 3);
  height: 3px; background:#111827;
  transform: translateX(0%);
  transition: transform .25s ease;
}
/* Panels */
#events-tabs .panel{ display:none; }
#events-tabs .panel.is-active{ display:block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const root = document.querySelector('#events-tabs');
  if(!root) return;

  const tabs = Array.from(root.querySelectorAll('.tab'));
  const underline = root.querySelector('.tab-underline');
  const panels = {
    all: root.querySelector('#panel-all'),
    upcoming: root.querySelector('#panel-upcoming'),
    past: root.querySelector('#panel-past')
  };

  function activate(name){
    tabs.forEach((t, i) => {
      const ok = t.dataset.tab === name;
      t.classList.toggle('is-active', ok);
      if(ok){ underline.style.transform = `translateX(${i*100}%)`; }
    });
    Object.entries(panels).forEach(([k, el]) => {
      el.classList.toggle('is-active', k === name);
    });
  }

  tabs.forEach(t => t.addEventListener('click', () => activate(t.dataset.tab)));

  // Optional: deep link ?tab=upcoming
  const url = new URL(window.location.href);
  const q = (url.searchParams.get('tab') || 'all').toLowerCase();
  if(['all','upcoming','past'].includes(q)) activate(q);
});
$candidate_texts = $events->map(fn($e) => $e->title . ' ' . ($e->summary ?? ''))->toArray();

</script>


@section('title', 'Events')

@section('content')
<section id="events-tabs" class="w-full lg:max-w-4xl max-w-[335px] mx-auto my-10">

  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">Events</h2>

    <div class="flex items-center gap-2">
      {{-- Full catalog --}}
      <a href="{{ route('events.index') }}"
         class="inline-flex items-center gap-2 rounded-sm bg-[#1b1b18] px-3 py-1.5 text-sm font-medium text-white hover:bg-black">
        View all
      </a>

      {{-- Nearby around Charguia 2 --}}
      <a href="{{ route('events.nearby', ['lat' => 36.86, 'lng' => 10.20, 'radius_km' => 20]) }}"
         class="inline-flex items-center gap-2 rounded-sm bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700">
        Near Charguia 2
      </a>
    </div>
  </div>

  {{-- Geofilter banner (shows only when controller provided lat/lng/radius) --}}
  @if(isset($lat, $lng, $radius))
    <div class="mb-4" style="border:1px solid #e5e7eb; border-radius:10px; padding:10px; background:#f8fafc;">
      <div class="flex flex-wrap items-center gap-2">
        <span class="text-sm text-zinc-600">
          Showing events near ({{ $lat }}, {{ $lng }}) within {{ $radius }} km
        </span>

        {{-- Radius presets (preserve lat/lng) --}}
        <a class="inline-flex items-center gap-2 rounded-sm bg-white px-3 py-1.5 text-sm font-medium border hover:bg-zinc-50"
           href="{{ route('events.nearby', ['lat'=>$lat,'lng'=>$lng,'radius_km'=>10]) }}">10 km</a>
        <a class="inline-flex items-center gap-2 rounded-sm bg-white px-3 py-1.5 text-sm font-medium border hover:bg-zinc-50"
           href="{{ route('events.nearby', ['lat'=>$lat,'lng'=>$lng,'radius_km'=>20]) }}">20 km</a>
        <a class="inline-flex items-center gap-2 rounded-sm bg-white px-3 py-1.5 text-sm font-medium border hover:bg-zinc-50"
           href="{{ route('events.nearby', ['lat'=>$lat,'lng'=>$lng,'radius_km'=>50]) }}">50 km</a>

        {{-- Use my location --}}
        <button type="button"
                class="inline-flex items-center gap-2 rounded-sm bg-white px-3 py-1.5 text-sm font-medium border hover:bg-zinc-50"
                onclick="(function geo(){if(!navigator.geolocation){alert('Geolocation unavailable');return;}navigator.geolocation.getCurrentPosition(function(p){const r={{ (int)($radius ?? 20) }}; const q=`?lat=${p.coords.latitude.toFixed(6)}&lng=${p.coords.longitude.toFixed(6)}&radius_km=${r}`; window.location.href='{{ route('events.nearby') }}'+q;},function(){alert('Location permission denied');});})()">
          Use my location
        </button>

        {{-- Clear filter --}}
        <a class="inline-flex items-center gap-2 rounded-sm bg-white px-3 py-1.5 text-sm font-medium border hover:bg-zinc-50"
           href="{{ route('events.index') }}">
          Clear filter
        </a>
      </div>
    </div>
  @endif

  {{-- Tabs --}}
  <div class="tabs">
    <button class="tab is-active" data-tab="all"><span>All</span></button>
    <button class="tab" data-tab="upcoming"><span>Upcoming</span></button>
    <button class="tab" data-tab="past"><span>Past</span></button>
    <span class="tab-underline"></span>
  </div>

  {{-- Panels --}}
  <div class="panels mt-6">
    {{-- All --}}
    <div class="panel is-active" id="panel-all">
      @if ($events->isEmpty())
        <p class="mt-6 text-center text-zinc-500">No public events available.</p>
      @else
        <div class="grid gap-6 md:grid-cols-2">
          @foreach ($events as $event)
            @include('events.partials._event-card', ['event' => $event])
          @endforeach
        </div>
        <div class="mt-8">
          {{-- Controller already chained ->withQueryString() --}}
          {{ $events->links() }}
        </div>
      @endif
    </div>

    {{-- Upcoming (fallback if controller did not provide a server-side collection) --}}
    <div class="panel" id="panel-upcoming">
      @php
        $upcoming = $events_all ?? \App\Models\Evenement::query()
          ->published()
          ->where('visibility', 'public')
          ->where('starts_at', '>=', now())
          ->orderBy('starts_at')
          ->paginate(12)
          ->withQueryString();
      @endphp
      @if ($upcoming->isEmpty())
        <p class="mt-6 text-center text-zinc-500">No upcoming events.</p>
      @else
        <div class="grid gap-6 md:grid-cols-2">
          @foreach ($upcoming as $event)
            @include('events.partials._event-card', ['event' => $event])
          @endforeach
        </div>
        <div class="mt-8">
          {{ $upcoming->links() }}
        </div>
      @endif
    </div>

    {{-- Past --}}
    <div class="panel" id="panel-past">
      @php
        $past = $events_past ?? \App\Models\Evenement::query()
          ->published()
          ->where('visibility', 'public')
          ->where('starts_at', '<', now())
          ->orderByDesc('starts_at')
          ->paginate(12)
          ->withQueryString();
      @endphp
      @if ($past->isEmpty())
        <p class="mt-6 text-center text-zinc-500">No past events.</p>
      @else
        <div class="grid gap-6 md:grid-cols-2">
          @foreach ($past as $event)
            @include('events.partials._event-card', ['event' => $event])
          @endforeach
        </div>
        <div class="mt-8">
          {{ $past->links() }}
        </div>
      @endif
    </div>
  </div>
</section>

{{-- Card partial hook (kept if you use stacks) --}}
@push('blade-templates')
  @verbatim
  @endverbatim
@endpush
@endsection
