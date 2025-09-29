@extends('layouts.app')

@section('title', 'Events')

@section('content')
<section id="events" class="w-full lg:max-w-4xl max-w-[335px] mx-auto my-10">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold">Upcoming Events</h2>
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-sm bg-[#1b1b18] px-3 py-1.5 text-sm font-medium text-white hover:bg-black">
            View all
        </a>
    </div>

    @if ($events->isEmpty())
    <p class="mt-6 text-center text-zinc-500">No public events available.</p>
    @else
    <div class="mt-6 grid gap-6 md:grid-cols-2">
        @foreach ($events as $event)
        <article class="rounded-lg bg-white shadow-xs hover:shadow transition">
            {{-- banner (image or gradient) --}}
            @if($event->cover_image_path)
            <img src="{{ Storage::url($event->cover_image_path) }}" alt="{{ $event->title }}" class="h-40 w-full rounded-t-lg object-cover" />
            @else
            <div class="h-40 w-full rounded-t-lg bg-linear-to-b from-neutral-100 to-white"></div>
            @endif

            <div class="p-5 space-y-2.5">
                <h3 class="truncate text-lg font-semibold leading-tight">{{ $event->title }}</h3>

                <p class="text-sm text-zinc-600">
                    {{ $event->starts_at->toDayDateTimeString() }}
                    @if($event->location_text)
                    • {{ $event->location_text }}
                    @endif
                </p>

                @if($event->summary)
                <p class="text-sm text-zinc-700 line-clamp-2">{{ $event->summary }}</p>
                @endif

                <div class="flex items-center justify-between text-xs text-zinc-500">
                    @if(is_null($event->capacity))
                    <span>Unlimited</span>
                    @else
                    <span>{{ $event->capacity_remaining }} left</span>
                    @endif
                    <span>Starts {{ $event->starts_at->diffForHumans() }}</span>
                </div>

                <div class="pt-1">
                    <a href="{{ route('events.show', $event->slug) }}" class="inline-block rounded-sm bg-[#1b1b18] px-4 py-1.5 text-sm text-white hover:bg-black">
                        View details
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $events->onEachSide(1)->links() }}
    </div>
    @endif
</section>
@endsection
