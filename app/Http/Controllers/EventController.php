<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;


class EventController extends Controller
{

public function index(Request $request)
{
    // 1. Get user's interest texts (events "interested" or "going")
    $user_history = auth()->user()
        ? auth()->user()->evenementsParticipating()
            ->wherePivotIn('status', ['interested', 'going'])
            ->get(['title', 'summary'])
        : collect();

    $user_texts = $user_history->map(fn($e) => $e->title . ' ' . ($e->summary ?? ''))->toArray();

    // 2. Fetch events candidates (e.g., nearby or all)
    // Get ALL candidate events (not paginated yet)
    $allEvents = Evenement::query()
        ->published()
        ->visibleTo($request->user())
        ->orderBy('starts_at')
        ->get(); // Use get(), not paginate() here

    $candidate_texts = $allEvents->map(fn($e) => $e->title . ' ' . ($e->summary ?? ''))->toArray();

    // 3. Prepare all texts for embedding (user interest + candidates)
    $texts = array_merge($user_texts, $candidate_texts);

    // 4. Call Cohere API to get embeddings
    $cohere_key = env('COHERE_API_KEY');
    $response = Http::withHeaders([
        'Authorization' => "Bearer $cohere_key",
        'Content-Type' => 'application/json',
    ])
        ->post('https://api.cohere.ai/v1/embed', [
            'texts' => $texts,
            'model' => 'embed-english-v3.0',
            'input_type' => 'search_document'
        ]);
    $embeddings = $response->json('embeddings');

    // Debug logs for testing
    logger()->info('User interest texts:', $user_texts);
    logger()->info('Candidate event texts:', $candidate_texts);
    logger()->info('Cohere API status:', ['status' => $response->status()]);
    logger()->info('Cohere API response body:', ['body' => $response->body()]);

    if (is_null($embeddings)) {
        logger()->error('Cohere embedding API failed: ' . json_encode($response->json()));
        // FALLBACK: simple pagination without AI ranking
        $events = $allEvents->paginate(12);
        return view('events.index', [
            'events' => $events,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'radius' => $request->radius_km,
        ]);
    }

    // 5. Split embeddings into user and candidate parts
    $user_embeds = array_slice($embeddings, 0, count($user_texts));
    $candidate_embeds = array_slice($embeddings, count($user_texts));

    // 6. Average user's embeddings or handle empty
    if (count($user_embeds) > 0) {
        $user_embed = array_map(
            fn(...$vals) => array_sum($vals) / count($vals),
            ...$user_embeds
        );
    } else {
        $user_embed = null;
    }

    // 7. Compute similarity scores for each candidate
    $cosine = function (array $a, array $b): float {
        $dot = 0;
        $magA = 0;
        $magB = 0;
        foreach ($a as $i => $x) {
            $dot += $x * $b[$i];
            $magA += $x * $x;
            $magB += $b[$i] * $b[$i];
        }
        return $magA && $magB ? $dot / (sqrt($magA) * sqrt($magB)) : 0;
    };

    if ($user_embed) {
        $scores = [];
        foreach ($candidate_embeds as $idx => $embedding) {
            $scores[$idx] = $cosine($embedding, $user_embed);
        }
        // Attach scores to events collection
        foreach ($scores as $idx => $score) {
            $allEvents[$idx]->ai_score = $score;
            logger()->info('Score for event', ['title' => $allEvents[$idx]->title, 'score' => $score]);
        }
        // Sort events by AI score descending
        $allEvents = $allEvents->sortByDesc('ai_score')->values();
        logger()->info('Sorted events sent to view:', $allEvents->map(fn ($e) => ['title' => $e->title, 'score' => $e->ai_score ?? 'N/A'])->toArray());
    }

    // 8. Manual pagination for sorted collection
    $perPage = 12;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $sliced = $allEvents->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $paginated = new LengthAwarePaginator(
        $sliced,
        $allEvents->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('events.index', [
        'events' => $paginated,
        'lat' => $request->lat,
        'lng' => $request->lng,
        'radius' => $request->radius_km,
    ]);
}


    public function show(Evenement $event)
    {
        $this->authorize('view', $event);

        $event->load('organizer');

        $userStatus = null;
        $ticket = null;
        if (auth()->check()) {
            $pivot = $event->participants()->where('user_id', auth()->id())->first()?->pivot;
            $userStatus = $pivot?->status;
            $ticketId = $pivot?->ticket_id;
            if ($ticketId) {
                $ticket = $event->tickets()->where('id', $ticketId)->first();
            }
        }

        $goingCount = $event->participants()->wherePivot('status', 'going')->count();
        $interestedCount = $event->participants()->wherePivot('status', 'interested')->count();

        return view('events.show', compact('event', 'userStatus', 'ticket', 'goingCount', 'interestedCount'));
    }

    public function nearby(Request $request)
    {
        $lat = (float) ($request->lat ?? 36.86);     // Charguia 2 default
        $lng = (float) ($request->lng ?? 10.20);     // Charguia 2 default
        $radius = (float) ($request->radius_km ?? 20);

        $events = \App\Models\Evenement::query()
            ->published()
            ->visibleTo($request->user())
            ->future()
            ->withinRadius($lat, $lng, $radius)
            ->with('organizer')
            ->paginate(12)
            ->withQueryString();

        return view('events.index', compact('events'));
    }
}
