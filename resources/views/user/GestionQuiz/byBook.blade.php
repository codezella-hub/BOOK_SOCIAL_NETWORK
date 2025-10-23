@extends('layouts.user-layout')

@section('title', 'Quiz du livre : ' . $book->title)

@section('content')
<div class="quiz-container">
    <h2>📘 Quiz du livre : {{ $book->title }}</h2>

    @forelse($quizzes as $quiz)
        <div class="quiz-card">
            <h4>{{ $quiz->title }}</h4>
            <p>{{ $quiz->description }}</p>
            <p><strong>Questions :</strong> {{ $quiz->questions_count }}</p>
            <a href="{{ route('user.quiz.show', ['book' => $book->id, 'quiz' => $quiz->id_quiz]) }}" class="btn btn-primary">
                Démarrer le quiz
            </a>
        </div>
    @empty
        <p>Aucun quiz disponible pour ce livre.</p>
    @endforelse
</div>
@endsection
