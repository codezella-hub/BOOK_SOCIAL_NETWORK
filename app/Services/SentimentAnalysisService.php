<?php
// app/Services/SentimentAnalysisService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentAnalysisService
{
    public function analyzeComment($comment)
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'deepseek/deepseek-r1-0528:free',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Analyse EXCLUSIVEMENT le sentiment de ce commentaire de livre. Réponds UNIQUEMENT par un seul mot : 'positive', 'negative' ou 'neutral'. Pas d'explications, pas de phrases, juste un mot.

                        Commentaire à analyser : \"$comment\""
                    ]
                ],
                'max_tokens' => 15,
                'temperature' => 0.1,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $rawResponse = $result['choices'][0]['message']['content'] ?? '';
                $sentiment = $this->parseSentimentResponse($rawResponse);

                Log::info('Analyse sentimentale', [
                    'comment' => $comment,
                    'raw_response' => $rawResponse,
                    'parsed_sentiment' => $sentiment
                ]);

                return $sentiment;
            }

            Log::error('Erreur API Sentiment Analysis', ['response' => $response->body()]);
            return $this->fallbackSentiment($comment);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'analyse sentimentale', ['error' => $e->getMessage()]);
            return $this->fallbackSentiment($comment);
        }
    }

    private function parseSentimentResponse($response)
    {
        $response = strtolower(trim($response));

        // Recherche des mots-clés dans la réponse
        if (str_contains($response, 'positive') || str_contains($response, 'positif')) {
            return 'positive';
        }

        if (str_contains($response, 'negative') || str_contains($response, 'negatif')) {
            return 'negative';
        }

        if (str_contains($response, 'neutral') || str_contains($response, 'neutre')) {
            return 'neutral';
        }

        // Si on ne trouve pas de mot-clé clair, on regarde le contenu
        if (str_contains($response, 'positive') || preg_match('/\b(positive|positif|bon|excellent|super|génial|parfait)\b/', $response)) {
            return 'positive';
        }

        if (str_contains($response, 'negative') || preg_match('/\b(negative|negatif|mauvais|déçu|décevant|pas bien|insatisfait)\b/', $response)) {
            return 'negative';
        }

        return 'neutral';
    }

    private function fallbackSentiment($comment)
    {
        // Analyse basique basée sur des mots-clés en français
        $positiveWords = ['excellent', 'super', 'génial', 'parfait', 'bon', 'bien', 'aimé', 'adore', 'fantastique', 'merveilleux', 'bravo', 'félicitations'];
        $negativeWords = ['mauvais', 'nul', 'déçu', 'décevant', 'horrible', 'pas bien', 'insatisfait', 'déception', 'mediocre', 'pire'];

        $comment = strtolower($comment);

        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($positiveWords as $word) {
            if (str_contains($comment, $word)) {
                $positiveCount++;
            }
        }

        foreach ($negativeWords as $word) {
            if (str_contains($comment, $word)) {
                $negativeCount++;
            }
        }

        if ($positiveCount > $negativeCount) {
            return 'positive';
        } elseif ($negativeCount > $positiveCount) {
            return 'negative';
        }

        return 'neutral';
    }
}
