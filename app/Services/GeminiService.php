<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $client;
    protected $apiKey;
    protected $model;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key');
        $this->model = config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * Envoie une question au chatbot Gemini spécialisé dans les livres
     */
    public function askAboutBooks(string $question, array $context = []): array
    {
        try {
            // Construire le prompt spécialisé pour les livres
            $prompt = $this->buildBookPrompt($question, $context);
            
            $response = $this->client->post($this->baseUrl . $this->model . ':generateContent', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'query' => [
                    'key' => $this->apiKey
                ],
                'json' => [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1024,
                    ]
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return [
                    'success' => true,
                    'response' => $data['candidates'][0]['content']['parts'][0]['text'],
                    'timestamp' => now()
                ];
            }

            return [
                'success' => false,
                'error' => 'Réponse inattendue de l\'API Gemini',
                'timestamp' => now()
            ];

        } catch (RequestException $e) {
            Log::error('Erreur API Gemini: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Désolé, je ne peux pas répondre pour le moment. Veuillez réessayer plus tard.',
                'timestamp' => now()
            ];
        }
    }

    /**
     * Construire un prompt spécialisé pour les questions sur les livres
     */
    private function buildBookPrompt(string $question, array $context = []): string
    {
        $basePrompt = "Tu es un assistant intelligent spécialisé dans les livres et la littérature. Tu aides les utilisateurs d'une plateforme sociale de partage de livres. Réponds de manière amicale, informative et concise en français.

CONTEXTE DE LA PLATEFORME:
- Plateforme de partage et donation de livres
- Les utilisateurs peuvent donner, emprunter et découvrir des livres
- Communauté d'amateurs de lecture

TON RÔLE:
- Recommander des livres selon les goûts
- Expliquer des concepts littéraires
- Aider à choisir quoi lire
- Donner des informations sur les auteurs
- Conseiller sur les genres littéraires
- Aider à organiser une bibliothèque personnelle

STYLE DE RÉPONSE:
- Amical et enthousiaste pour la lecture
- Réponses de 50-200 mots maximum
- Utilise des emojis quand approprié
- Propose des alternatives ou suggestions
- Reste sur le sujet des livres et de la lecture";

        // Ajouter le contexte si fourni (ex: livre spécifique)
        if (!empty($context)) {
            $basePrompt .= "\n\nCONTEXTE SPÉCIFIQUE:\n";
            if (isset($context['book_title'])) {
                $basePrompt .= "- Livre: " . $context['book_title'];
            }
            if (isset($context['author'])) {
                $basePrompt .= " par " . $context['author'];
            }
            if (isset($context['genre'])) {
                $basePrompt .= "\n- Genre: " . $context['genre'];
            }
            if (isset($context['description'])) {
                $basePrompt .= "\n- Description: " . $context['description'];
            }
        }

        $basePrompt .= "\n\nQUESTION DE L'UTILISATEUR: " . $question;
        
        return $basePrompt;
    }

    /**
     * Obtenir des suggestions de livres basées sur un livre donné
     */
    public function getSimilarBooks(string $bookTitle, string $author = '', string $genre = ''): array
    {
        $question = "Peux-tu me recommander des livres similaires à \"$bookTitle\"";
        if ($author) {
            $question .= " de $author";
        }
        $question .= " ? Je cherche des suggestions dans le même style ou genre.";

        $context = [
            'book_title' => $bookTitle,
            'author' => $author,
            'genre' => $genre
        ];

        return $this->askAboutBooks($question, $context);
    }

    /**
     * Obtenir des informations sur un auteur
     */
    public function getAuthorInfo(string $author): array
    {
        $question = "Peux-tu me parler de l'auteur $author ? Ses œuvres principales, son style, et quelques livres que tu recommandes de cet auteur ?";
        
        return $this->askAboutBooks($question);
    }

    /**
     * Obtenir des recommandations par genre
     */
    public function getRecommendationsByGenre(string $genre): array
    {
        $question = "Peux-tu me recommander quelques excellents livres du genre $genre ? Avec une brève description de pourquoi ils sont remarquables.";
        
        return $this->askAboutBooks($question);
    }
}