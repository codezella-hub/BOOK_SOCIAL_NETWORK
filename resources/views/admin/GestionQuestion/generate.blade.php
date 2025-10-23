@extends('layouts.admin-layout')

@section('title', 'Génération IA')
@section('page-title', 'Génération de QCM via Intelligence Artificielle')

@section('styles')
<style>
.page-wrapper {
  max-width: 1100px;
  margin: 30px auto;
  background: #ffffff;
  border-radius: 16px;
  padding: 35px;
  box-shadow: 0 6px 25px rgba(0,0,0,0.08);
  font-family: 'Roboto', sans-serif;
}
.page-header {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
}
.page-header i {
  font-size: 2em;
  color: #4f46e5;
}
.page-header h2 {
  margin: 0;
  font-size: 1.8em;
  color: #1e1e2d;
  font-weight: 700;
}
.page-subtitle {
  color: #6b7280;
  font-size: 0.95em;
  margin-top: 5px;
}
.section {
  margin-top: 25px;
  background: #fafafa;
  border-radius: 10px;
  padding: 25px;
  border: 1px solid #eee;
  transition: 0.2s ease;
}
.section:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
.section h4 {
  color: #374151;
  margin-bottom: 15px;
  font-weight: 600;
  font-size: 1.1em;
}
label { font-weight: 600; color: #374151; margin-bottom: 6px; display: block; }
input[type="text"], input[type="number"], textarea, select {
  width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px;
  transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
input:focus, textarea:focus, select:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
  outline: none;
}
.helper-text { font-size: 0.85em; color: #6b7280; margin-top: 4px; }
.param-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
  gap: 18px;
}
.btn-group {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 25px;
}
.btn {
  padding: 12px 20px;
  border-radius: 10px;
  border: none;
  cursor: pointer;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: 0.2s;
}
.btn i { font-size: 1em; }
.btn-primary { background: #4f46e5; color: #fff; }
.btn-success { background: #22c55e; color: #fff; }
.btn-secondary { background: #6b7280; color: #fff; }
.btn:hover { opacity: 0.9; transform: translateY(-2px); }
.loader {
  display: none;
  margin-top: 20px;
  text-align: center;
  color: #4f46e5;
}
.loader i { font-size: 1.6em; animation: spin 1.2s linear infinite; }
@keyframes spin { from { transform: rotate(0deg);} to { transform: rotate(360deg);} }
.preview-section { margin-top: 35px; display: none; }
.preview-card {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  padding: 18px;
  margin-bottom: 12px;
}
.preview-card:hover { background: #eef2ff; transform: translateY(-2px); }
.preview-card h5 { margin: 0 0 5px; color: #4f46e5; font-size: 1.05em; }
.preview-card ul { list-style: none; padding: 0; margin: 0; }
.preview-card li { padding: 3px 0; }
.alert {
  padding: 10px 14px;
  border-radius: 6px;
  font-size: 0.9em;
  margin-bottom: 10px;
}
.alert-success { background: #d1fae5; color: #065f46; }
.alert-error { background: #fee2e2; color: #991b1b; }
.alert-warning { background: #fef3c7; color: #92400e; }
@media (max-width: 768px) { .page-wrapper { padding: 20px; } }
</style>
@endsection


@section('content')
<div class="page-wrapper">
  <div class="page-header">
    <i class="fas fa-robot"></i>
    <div>
      <h2>Génération de QCM par Intelligence Artificielle</h2>
      <p class="page-subtitle">
        Utilisez notre IA pour créer automatiquement des questions à partir du contenu du livre sélectionné.
      </p>
    </div>
  </div>

  {{-- 🔔 ALERTS --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-warning">
      <ul style="margin:0;padding-left:18px;">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- 🧠 FORMULAIRE IA --}}
  <form id="generateForm" method="POST"
      action="{{ route('admin.quiz.question.generate', $quiz) }}"

 >
      @csrf

      <div class="section">
        <h4><i class="fas fa-book"></i> Contexte du livre</h4>
        <label>Titre du livre *</label>
        <input type="text" name="book_title" required
          value="{{ old('book_title', $defaults['book_title'] ?? $quiz->book->title ?? $quiz->title) }}">

        <label style="margin-top:10px;">Résumé (modifiable)</label>
        <textarea name="book_summary" rows="3">{{ old('book_summary', $defaults['book_summary'] ?? '') }}</textarea>

        <label style="margin-top:10px;">Extrait ou passage ciblé (optionnel)</label>
        <textarea name="book_excerpt" rows="5">{{ old('book_excerpt', $defaults['book_excerpt'] ?? '') }}</textarea>
        <div class="helper-text">Plus l’extrait est précis, plus les questions seront pertinentes.</div>
      </div>

      <div class="section">
        <h4><i class="fas fa-sliders-h"></i> Paramètres de génération</h4>
        <div class="param-grid">
          <div>
            <label>Nombre de questions *</label>
            <input type="number" name="num_questions" min="1" max="20" value="{{ old('num_questions',5) }}" required>
          </div>
          <div>
            <label>Difficulté *</label>
            <select name="difficulty" required>
              <option value="beginner" {{ old('difficulty')==='beginner'?'selected':'' }}>Débutant</option>
              <option value="intermediate" {{ old('difficulty','intermediate')==='intermediate'?'selected':'' }}>Intermédiaire</option>
              <option value="advanced" {{ old('difficulty')==='advanced'?'selected':'' }}>Avancé</option>
            </select>
          </div>
          <div>
            <label>Points / question *</label>
            <input type="number" step="0.5" min="0.5" max="10" name="points_per_question"
              value="{{ old('points_per_question',1) }}" required>
          </div>
        </div>
      </div>

      <div class="btn-group">
          <a href="{{ route('admin.quiz.question.index', $quiz) }}"
             class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Annuler
          </a>

          <button type="button" id="previewBtn" class="btn btn-primary">
              <i class="fas fa-eye"></i> Prévisualiser
          </button>

          <button type="submit" id="generateBtn" class="btn btn-success">
              <i class="fas fa-magic"></i> Générer & Enregistrer
          </button>
      </div>

      <div class="loader" id="loadingSpinner">
        <i class="fas fa-circle-notch"></i> Génération en cours...
      </div>
  </form>

  <div id="previewSection" class="preview-section">
      <h4><i class="fas fa-lightbulb"></i> Aperçu des questions générées</h4>
      <div id="previewContent"></div>
      <button type="button" id="confirmGenerate" class="btn btn-success">
          <i class="fas fa-save"></i> Confirmer & sauvegarder
      </button>
  </div>
</div>
@endsection


@section('scripts')
<script>
const form = document.getElementById('generateForm');
const previewBtn = document.getElementById('previewBtn');
const previewSection = document.getElementById('previewSection');
const previewContent = document.getElementById('previewContent');
const loading = document.getElementById('loadingSpinner');
const confirmBtn = document.getElementById('confirmGenerate');

function toggleLoader(show) {
  loading.style.display = show ? 'block' : 'none';
}

previewBtn.addEventListener('click', async () => {
  toggleLoader(true);
  previewSection.style.display = 'none';
  previewContent.innerHTML = '';

  try {
    const fd = new FormData(form);
    const res = await fetch('{{ route("admin.quiz.question.generate.preview", $quiz) }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: fd
    });
    const data = await res.json();
    if (!data.success) throw new Error(data.message || 'Erreur lors de la génération.');

    data.questions.forEach((q, i) => {
      const card = document.createElement('div');
      card.className = 'preview-card';
      card.innerHTML = `
        <h5>Question ${i+1}</h5>
        <p>${q.question_text}</p>
        <ul>
          <li>${q.correct_answer==='A'?'✅':''} A) ${q.option_a}</li>
          <li>${q.correct_answer==='B'?'✅':''} B) ${q.option_b}</li>
          <li>${q.correct_answer==='C'?'✅':''} C) ${q.option_c}</li>
          <li>${q.correct_answer==='D'?'✅':''} D) ${q.option_d}</li>
        </ul>
        ${q.explanation ? `<div class="explanation"><strong>Explication :</strong> ${q.explanation}</div>` : ''}
      `;
      previewContent.appendChild(card);
    });

    previewSection.style.display = 'block';
    previewSection.scrollIntoView({ behavior: 'smooth' });
  } catch (e) {
    alert(e.message);
  } finally {
    toggleLoader(false);
  }
});

confirmBtn.addEventListener('click', () => form.submit());
</script>
@endsection
