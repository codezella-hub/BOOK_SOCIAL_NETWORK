<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Str;

class ContentModerator
{
    protected Client $http;
    protected string $token;
    protected string $model;
    protected float $threshold;

   
protected array $badWords = [
    'shit', 'fuck', 'bitch', 'bastard', 'asshole', 'dick', 'piss', 'slut', 'whore', 'moron',
    'stupid', 'idiot', 'jerk', 'crap', 'damn', 'motherfucker', 'fucker', 'douche', 'bollocks',
    'wanker', 'prick', 'cunt', 'twat', 'retard', 'loser', 'dumbass', 'suck', 'sucker',
    'arse', 'bugger', 'shithead', 'scumbag',

    'con', 'connard', 'connasse', 'salope', 'batard', 'putain', 'merde', 'encule', 'enculer',
    'tafiole', 'pd', 'bite', 'couille', 'chienne', 'pute', 'grognasse', 'ordure', 'abruti',
    'cretin', 'idiot', 'debile', 'nul', 'foutre', 'branleur', 'bouffon', 'naze',

    'f*ck', 'f**k', 'f#ck', 'sh1t', 'b!tch', 'b1tch', 'a$$', 'd!ck', 'p!ss', 'f u c k',
    'merd', 'encul', 'p***', 'conn', 'batrd', 'stup1d', 'sh*t', 'idi0t', 'b!tch', 'wh0re',

    'racist', 'nigger', 'negro', 'faggot', 'chink', 'spic', 'kike', 'terrorist',

    'kill yourself', 'go die', 'die bitch', 'i’ll kill you', 'suicide', 'hang yourself'
];

    public function __construct()
    {
        $this->http      = new Client([
            'base_uri' => 'https://api-inference.huggingface.co',
            'timeout'  => 12,
        ]);
        $this->token     = (string) config('services.huggingface.token', env('HF_TOKEN'));
        $this->model     = (string) env('HF_MODEL', 'unitary/toxic-bert');
        $this->threshold = (float) env('HF_TOXICITY_THRESHOLD', 0.75);
    }

    /**
     * Analyze text with HF Inference API and return [scores[], is_toxic(bool)]
     */
    public function analyze(string $text): array
    {
        if (trim($text) === '') {
            return ['scores' => [], 'is_toxic' => false];
        }

        try {
            $res = $this->http->post("/models/{$this->model}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ],
                'json' => ['inputs' => $text],
                'http_errors' => false,
            ]);

            $body = json_decode((string) $res->getBody(), true);

            // Most toxicity classifiers return an array of label/score pairs.
            $pairs = is_array($body) && isset($body[0]) && is_array($body[0]) ? $body[0] : $body;
            $scores = [];
            foreach ($pairs as $p) {
                if (isset($p['label'], $p['score'])) {
                    $scores[$p['label']] = (float) $p['score'];
                }
            }

            $max = empty($scores) ? 0.0 : max($scores);
            return ['scores' => $scores, 'is_toxic' => $max >= $this->threshold];
        } catch (\Throwable $e) {
            // Fail-safe: don’t block users if the API is down
            return ['scores' => [], 'is_toxic' => false];
        }
    }

    /**
     * Mask a single word as **** preserving length.
     */
    protected function maskWord(string $word): string
    {
        $len = mb_strlen($word);
        return str_repeat('*', max(3, $len)); // at least *** so it’s visible
    }

    /**
     * Replace profanities with **** (case-insensitive, word boundaries).
     * Also catches basic leetspeak like @ for a, 1 for i, etc. (simple normalize).
     */
    public function maskProfanities(string $text): string
    {
        // Normalize basic leetspeak for matching (without changing original text)
        $normalize = static fn (string $s) => strtr(
            Str::lower($s),
            ['@' => 'a', '4' => 'a', '1' => 'i', '!' => 'i', '0' => 'o', '$' => 's', '3' => 'e', '7' => 't']
        );

        // Build regex for whole-word matching, safe for unicode
        $escaped = array_map(fn ($w) => preg_quote($w, '/'), $this->badWords);
        $pattern = '/(' . implode('|', $escaped) . ')/iu';

        // First pass: strict words
        $text = preg_replace_callback($pattern, function ($m) {
            return $this->maskWord($m[0]);
        }, $text);

        // Second pass (optional): loose matching using normalization, to catch obfuscations
        $words = preg_split('/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($words as &$w) {
            // Skip whitespace tokens
            if (preg_match('/^\s+$/u', $w)) continue;

            $norm = $normalize($w);
            foreach ($this->badWords as $bw) {
                if (preg_match('/\b' . preg_quote($bw, '/') . '\b/u', $norm)) {
                    $w = $this->maskWord($w);
                    break;
                }
            }
        }
        unset($w);

        return implode('', $words);
    }

    /**
     * Main entry: clean + analysis. Returns array with:
     *  - clean (masked text), toxic(bool), scores(array)
     */
    public function moderate(string $text): array
    {
        $analysis = $this->analyze($text);
        $clean    = $this->maskProfanities($text);

        return [
            'clean'   => $clean,
            'toxic'   => $analysis['is_toxic'],
            'scores'  => $analysis['scores'],
        ];
    }
}
