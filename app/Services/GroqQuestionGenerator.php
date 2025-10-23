<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqQuestionGenerator
{
    public function generate(array $input): array
    {
        $p = $this->normalize($input);

        $base = rtrim(env('AI_API_BASE', 'https://api.groq.com/openai/v1'), '/');
        $headers = [
            'Authorization' => 'Bearer '.env('AI_API_KEY'),
            'Content-Type'  => 'application/json',
        ];

        // Groq suit l’API OpenAI /chat/completions
        // "json_schema" n'est pas toujours garanti -> on utilise "json_object" + consigne stricte
        $payload = [
            'model' => env('AI_MODEL', 'llama-3.1-8b-instant'),
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert qui génère des QCM en français, pédagogiques et clairs.'],
                ['role' => 'user',   'content' => $this->userPrompt($p)],
            ],
            'temperature' => 0.7,
            'max_tokens'  => 2000,
            'response_format' => ['type' => 'json_object'], // force une réponse JSON
        ];

        $resp = Http::timeout((int) env('AI_TIMEOUT', 60))
            ->withHeaders($headers)
            ->post($base.'/chat/completions', $payload);

        if (!$resp->ok()) {
            Log::error('Groq error '.$resp->status().': '.$resp->body());
            throw new \RuntimeException('Le service IA (Groq) ne répond pas.');
        }

        $data = $resp->json();
        $content = (string) Arr::get($data, 'choices.0.message.content', '');

        // Parse JSON
        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            // fallback très robuste: tente extraction JSON dans un texte (rare)
            $content = $this->extractJson($content);
            $decoded = json_decode($content, true);
        }
        if (!is_array($decoded)) {
            throw new \RuntimeException('Réponse IA invalide (JSON).');
        }

        // Normalisation
        $out = [];
        foreach (Arr::get($decoded, 'questions', []) as $q) {
            $out[] = [
                'question_text'  => trim($q['question_text'] ?? ''),
                'option_a'       => trim(Arr::get($q, 'options.A', '')),
                'option_b'       => trim(Arr::get($q, 'options.B', '')),
                'option_c'       => trim(Arr::get($q, 'options.C', '')),
                'option_d'       => trim(Arr::get($q, 'options.D', '')),
                'correct_answer' => strtoupper($q['correct_answer'] ?? ''),
                'explanation'    => trim($q['explanation'] ?? ''),
            ];
        }

        // Filtre final
        return array_values(array_filter($out, function ($q) {
            $has4 = $q['option_a'] && $q['option_b'] && $q['option_c'] && $q['option_d'];
            $ans  = in_array($q['correct_answer'], ['A','B','C','D'], true);
            return $q['question_text'] && $has4 && $ans;
        }));
    }

    private function userPrompt(array $p): string
    {
        $ctx  = "Livre: {$p['book_title']}\n";
        if ($p['book_summary']) $ctx .= "Résumé: {$p['book_summary']}\n";
        if ($p['book_excerpt']) $ctx .= "Extrait: {$p['book_excerpt']}\n";

        // On décrit le schéma explicitement pour aider le modèle
        return <<<TXT
Génère {$p['num_questions']} QCM (4 options A,B,C,D) de niveau {$p['difficulty']}.
Contraintes:
- 1 seule bonne réponse
- Options plausibles, distinctes, sans pièges
- Français, clair et concis
- Réponds UNIQUEMENT en JSON valide suivant ce schéma :

{
  "questions": [
    {
      "question_text": "string",
      "options": {"A": "string", "B": "string", "C": "string", "D": "string"},
      "correct_answer": "A|B|C|D",
      "explanation": "string"
    }
  ]
}

Contexte:
$ctx
TXT;
    }

    private function normalize(array $data): array
    {
        $difficultyText = match($data['difficulty'] ?? 'intermediate') {
            'beginner'     => 'débutant (questions simples et directes)',
            'intermediate' => 'intermédiaire (questions nécessitant réflexion)',
            'advanced'     => 'avancé (questions complexes et analytiques)',
            default        => 'intermédiaire',
        };

        return [
            'book_title'    => (string) ($data['book_title'] ?? ''),
            'book_summary'  => (string) ($data['book_summary'] ?? ''),
            'book_excerpt'  => $this->truncate((string) ($data['book_excerpt'] ?? ''), 3000),
            'num_questions' => (int) ($data['num_questions'] ?? 5),
            'difficulty'    => $difficultyText,
        ];
    }

    private function truncate(string $text, int $max): string
    {
        $text = trim(strip_tags($text));
        return mb_strlen($text) > $max ? (mb_substr($text, 0, $max - 1).'…') : $text;
    }

    private function extractJson(string $text): string
    {
        // Essaie de récupérer le plus gros bloc JSON
        $start = strpos($text, '{');
        $end   = strrpos($text, '}');
        if ($start !== false && $end !== false && $end > $start) {
            return substr($text, $start, $end - $start + 1);
        }
        return $text;
    }
}
