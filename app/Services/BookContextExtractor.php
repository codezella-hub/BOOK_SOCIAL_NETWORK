<?php

namespace App\Services;

use App\Models\Book;

class BookContextExtractor
{
    /**
     * Construit le contexte IA à partir de TON modèle Book.
     */
    public function extract(Book $book, int $excerptMax = 3000): array
    {
        $title   = (string) ($book->title ?? '');
        $summary = (string) ($book->synopsis ?? '');

        // Ici, tu n'as pas de champ 'content' dans Book.
        // On utilisera par défaut le synopsis comme "extrait",
        // et on laisse l'admin coller un passage précis dans le formulaire si besoin.
        $excerpt = $this->truncate(strip_tags($summary), $excerptMax);

        // Infos utiles (optionnelles) : auteur, isbn
        $author  = (string) ($book->author_name ?? '');
        $isbn    = (string) ($book->isbn ?? '');

        $meta = '';
        if ($author) { $meta .= "Auteur: {$author}\n"; }
        if ($isbn)   { $meta .= "ISBN: {$isbn}\n"; }

        return [
            'book_title'   => $title,
            'book_summary' => trim($meta . ($summary ?: '')),
            'book_excerpt' => $excerpt,
        ];
    }

    private function truncate(string $text, int $max): string
    {
        $text = trim($text);
        return mb_strlen($text) > $max ? (mb_substr($text, 0, $max - 1).'…') : $text;
    }
}
