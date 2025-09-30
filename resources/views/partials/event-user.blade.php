<!-- Events Section (book-style) -->
<section class="books" id="popular-events">
<style>
/* Card base spacing */
#popular-events .book-card {
  display: flex;
  flex-direction: column;
  border-radius: 14px;
  overflow: hidden;
}

/* Cover: keep stable height and add a soft bottom fade for legibility */
#popular-events .book-cover {
  height: 180px;
  background-size: cover;
  background-position: center;
  position: relative;
}
#popular-events .book-cover::after {
  content: "";
  position: absolute; inset: 0;
  background: linear-gradient(to bottom, rgba(0,0,0,0) 60%, rgba(0,0,0,.06));
}

/* Body spacing */
#popular-events .book-info {
  display: grid;
  gap: 8px;
  padding: 12px 14px 14px;
}

/* Title clamp to 1 line */
#popular-events .book-title {
  font-weight: 800;
  color: #0f172a;
  font-size: 1rem;
  line-height: 1.2;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Meta line: time + location neatly aligned */
#popular-events .book-author {
  color: #6b7280;
  font-size: .9rem;
  display: flex;
  align-items: center;
  gap: .5rem;
}
#popular-events .book-author i { color:#6b7280; }

/* Metrics as a clean pill row */
#popular-events .book-rating {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  padding: 6px 8px;
  border: 1px solid #eef2f7;
  border-radius: 999px;
  background: #fafafa;
}

/* Each metric item */
#popular-events .book-rating span {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  line-height: 1;
  white-space: nowrap;
}

/* Colors for icons */
#popular-events .book-rating .fa-users { color:#6366f1; }  /* indigo */
#popular-events .book-rating .fa-chair { color:#f59e0b; }  /* amber */

/* The trailing "Starts X" is dimmer and non-wrapping */
#popular-events .book-rating span:last-child {
  color:#6b7280;
}

/* Actions row: two icons aligned left, balanced padding */
#popular-events .book-actions {
  display: flex;
  gap: 8px;
  padding-top: 4px;
}
#popular-events .book-actions .action-btn {
  width: 34px; height: 34px;
  display:inline-flex; align-items:center; justify-content:center;
  border:1px solid #e5e7eb; border-radius:10px;
  background:#fff; color:#374151;
  transition: transform .05s ease, background .2s;
}
#popular-events .book-actions .action-btn:hover {
  background:#f9fafb; transform: translateY(-1px);
}

/* Icon sizing for consistency */
#popular-events .book-actions i,
#popular-events .book-rating i,
#popular-events .book-author i {
  font-size: 14px;
}

/* Prevent text and icons from colliding on small screens */
@media (max-width: 480px) {
  #popular-events .book-cover { height: 160px; }
  #popular-events .book-rating { gap: 8px; }
  #popular-events .book-title { font-size: .98rem; }
}

/* Slight card shadow on hover for depth */
#popular-events .book-card {
  box-shadow: 0 8px 18px rgba(5,7,9,.05);
  border: 1px solid #eef2f7;
}
#popular-events .book-card:hover {
  box-shadow: 0 12px 26px rgba(5,7,9,.08);
}
</style>




<script>
// Generic carousel initializer (safe for multiple instances)
(function(){
  function initCarousel(trackSel, dotsSel, autoplay=false, interval=6000){
    const track = document.querySelector(trackSel);
    const dots = Array.from(document.querySelectorAll(dotsSel));
    if(!track || dots.length === 0) return;

    const viewport = track.closest('.books-carousel');
    if(!viewport) return;

    let index = 0;
    function goTo(i){
      index = i;
      const w = viewport.clientWidth;
      track.style.transform = `translateX(-${w * index}px)`;
      dots.forEach((d, idx) => d.classList.toggle('active', idx === index));
    }

    dots.forEach((d, i) => d.addEventListener('click', () => goTo(i)));

    // Keep aligned on resize
    let t = null;
    window.addEventListener('resize', () => {
      clearTimeout(t);
      t = setTimeout(() => goTo(index), 120);
    });

    // Optional autoplay
    let timer = null;
    function start(){ if(autoplay && dots.length>1) timer = setInterval(()=>goTo((index+1)%dots.length), interval); }
    function stop(){ if(timer) clearInterval(timer); }
    viewport.addEventListener('mouseenter', stop);
    viewport.addEventListener('mouseleave', start);

    // Smooth transition
    track.style.transition = 'transform 400ms ease';

    goTo(0);
    start();
  }

  // Initialize only the events carousel here
  document.addEventListener('DOMContentLoaded', () => {
    initCarousel('#events-track', '#events-dots .carousel-dot', false, 6000);
  });
})();
</script>










  <div class="container">
    <div class="section-title fade-in">
      <h2>Événements populaires</h2>
      <p>Découvrez les événements publics à venir dans la communauté.</p>
    </div>

    @php
      $eventsCarousel = \App\Models\Evenement::query()
        ->published()
        ->where('visibility', 'public')
        ->orderBy('starts_at')
        ->take(12)
        ->get();
    @endphp

    @if($eventsCarousel->isEmpty())
      <p class="mt-6 text-center" style="color:#6b7280">Aucun événement public pour le moment.</p>
    @else
      <div class="books-carousel events-carousel">
        <div class="books-track" id="events-track">
          @foreach($eventsCarousel as $event)
            <div class="book-card">
              <div class="book-cover"
                   @if($event->cover_image_path)
                     style="background-image: url('{{ Storage::url($event->cover_image_path) }}')"
                   @else
                     style="background-image: linear-gradient(145deg,#eef2ff,#fafafa)"
                   @endif>
                <div class="book-favorite">
                  <i class="far fa-calendar"></i>
                </div>
              </div>

              <div class="book-info">
                <div class="book-title">{{ $event->title }}</div>

                <div class="book-author" style="display:flex;gap:.5rem;flex-wrap:wrap">
                  <span>
                    <i class="far fa-clock"></i>
                    {{ $event->starts_at->toDayDateTimeString() }}
                  </span>
                  @if($event->location_text)
                    <span>• {{ $event->location_text }}</span>
                  @endif
                </div>

                <div class="book-rating" style="gap:.35rem">
                  {{-- Interested count (expects $event->interested_count to be available; shows 0 if not) --}}
                  <span title="Intéressés" style="font-size:.9rem;color:#6366f1;display:inline-flex;align-items:center;gap:.35rem">
                    <i class="fas fa-users"></i>
                    <strong>{{ $event->interested_count ?? 0 }}</strong>
                  </span>

                  @if(is_null($event->capacity))
                    <span title="Illimité" style="font-size:.9rem;color:#10b981">
                      <i class="fas fa-chair"></i>
                    </span>
                  @else
                    <span title="Places restantes" style="font-size:.9rem;color:#f59e0b">
                      <i class="fas fa-chair"></i>
                    </span>
                    <span style="font-weight:700;color:#f59e0b">{{ $event->capacity_remaining }}</span>
                  @endif

                  <span style="font-size:.9rem;color:#6b7280">
                    • Starts {{ $event->starts_at->diffForHumans() }}
                  </span>
                </div>

                <div class="book-actions">
                  <a class="action-btn" href="{{ route('events.show', $event->slug) }}" title="Voir les détails">
                    <i class="fas fa-eye"></i>
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Dedicated dots for events -->
        <div class="carousel-nav" id="events-dots">
          <div class="carousel-dot active" data-slide="0"></div>
          <div class="carousel-dot" data-slide="1"></div>
          <div class="carousel-dot" data-slide="2"></div>
        </div>
      </div>

      <div class="load-more">
        <a href="{{ route('events.index') }}" class="btn btn-light">
          <i class="fas fa-plus"></i> Voir plus d’événements
        </a>
      </div>
    @endif
  </div>
</section>

@push('scripts')


@endpush
