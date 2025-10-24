<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Donation;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    /**
     * Afficher l'interface du chatbot
     */
    public function index(Request $request)
    {
        $context = [];
        
        // Si on vient depuis une donation, récupérer le contexte
        if ($request->has('donation_id')) {
            $donation = Donation::find($request->donation_id);
            if ($donation) {
                $context = [
                    'book_title' => $donation->book_title,
                    'author' => $donation->author,
                    'genre' => $donation->genre,
                    'description' => $donation->description
                ];
            }
        }

        return view('chatbot.index', compact('context'));
    }

    /**
     * Traiter une question du chatbot via AJAX
     */
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'context' => 'sometimes|array'
        ]);

        $question = $request->input('question');
        $context = $request->input('context', []);

        // Obtenir la réponse de Gemini
        $result = $this->geminiService->askAboutBooks($question, $context);

        return response()->json($result);
    }

    /**
     * Obtenir des recommandations similaires à un livre
     */
    public function getSimilarBooks(Request $request)
    {
        $request->validate([
            'book_title' => 'required|string|max:255',
            'author' => 'sometimes|string|max:255',
            'genre' => 'sometimes|string|max:255'
        ]);

        $result = $this->geminiService->getSimilarBooks(
            $request->book_title,
            $request->author ?? '',
            $request->genre ?? ''
        );

        return response()->json($result);
    }

    /**
     * Obtenir des informations sur un auteur
     */
    public function getAuthorInfo(Request $request)
    {
        $request->validate([
            'author' => 'required|string|max:255'
        ]);

        $result = $this->geminiService->getAuthorInfo($request->author);

        return response()->json($result);
    }

    /**
     * Obtenir des recommandations par genre
     */
    public function getRecommendationsByGenre(Request $request)
    {
        $request->validate([
            'genre' => 'required|string|max:255'
        ]);

        $result = $this->geminiService->getRecommendationsByGenre($request->genre);

        return response()->json($result);
    }

    /**
     * Chatbot spécialisé depuis une donation spécifique
     */
    public function fromDonation($donationId)
    {
        $donation = Donation::findOrFail($donationId);
        
        $context = [
            'book_title' => $donation->book_title,
            'author' => $donation->author,
            'genre' => $donation->genre,
            'description' => $donation->description
        ];

        return view('chatbot.index', compact('context', 'donation'));
    }
}
