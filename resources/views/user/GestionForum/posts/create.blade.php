@extends('layouts.user-layout')

@section('title', 'Créer un Post')
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <h1 class="post-title">Create a New Post</h1>
        <p>Share your thoughts with the community</p>
        <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="post-form-container">
        <form action="{{ route('user.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="topic_id" class="form-label required">Post Topic</label>
                <select name="topic_id" id="topic_id" class="form-control" required>
                    <option value="">Select a topic</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id') == $topic->id ? 'selected' : '' }}>
                            {{ $topic->title }}
                        </option>
                    @endforeach
                </select>
                @error('topic_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="content_P" class="form-label required">Post Content</label>
                <textarea name="content_P" id="content_P" rows="6" class="form-control" 
                          placeholder="Share your thoughts, questions, or experiences..." 
                          required>{{ old('content_P') }}</textarea>

                {{-- statut + aperçu live (sans toucher au textarea) --}}
                <small id="moderation-status-post" class="text-muted"></small>
                <div id="moderation-preview-post" class="moderation-preview" aria-live="polite"></div>
                <div class="moderation-actions" style="margin-top:.5rem">
                    <button type="button" id="applyClean" class="btn btn-light btn-xs" disabled>
                        Apply cleaned text
                    </button>
                </div>

                @error('content_P')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="char-count">
                    <span id="charCount">0</span>/1000 characters
                </div>
            </div>

            {{-- Image (optionnelle) - uploader moderne --}}
            <div class="form-group">
              <label class="form-label">Image <span class="text-muted">(optional)</span></label>

              <div class="upload-card" id="postImageUpload"
                   data-max="2097152"  {{-- 2MB --}}
                   data-existing="">
                <input id="image" name="image" type="file" accept="image/*" class="upload-input" hidden>

                <div class="upload-drop">
                  <i class="fas fa-cloud-upload-alt upload-icon" aria-hidden="true"></i>
                  <div class="upload-text">
                    <strong>Drag & drop your image</strong> or
                    <button type="button" class="btn btn-light btn-xs upload-choose">Choose a file</button>
                    <div class="upload-hint">jpg, jpeg, png, webp, gif — max 2MB</div>
                  </div>
                </div>

                <div class="upload-meta">
                  <span class="upload-filename" data-default="No file selected">No file selected</span>
                  <button type="button" class="btn btn-ghost btn-xs upload-remove" hidden>Remove</button>
                </div>

                <div class="upload-preview" hidden>
                  <img alt="Image preview" />
                </div>
              </div>

              @error('image')
                <div class="error-message">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Publish Post
                </button>
                <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
/* ====== Modération live ====== */
document.addEventListener('DOMContentLoaded', () => {
  const MOD_URL = "{{ route('moderate.live') }}";
  const tx       = document.getElementById('content_P');
  const statusEl = document.getElementById('moderation-status-post');
  const preview  = document.getElementById('moderation-preview-post');
  const applyBtn = document.getElementById('applyClean');
  const csrftok  = document.querySelector('meta[name="csrf-token"]').content;
  const charCount= document.getElementById('charCount');

  const updateCount = () => charCount && (charCount.textContent = tx.value.length);
  const updateApplyState = () => {
    const clean = (preview.textContent || '').trim();
    applyBtn.disabled = !clean || clean === tx.value;
  };
  updateCount(); updateApplyState();

  let composing = false;
  tx.addEventListener('compositionstart', () => composing = true);
  tx.addEventListener('compositionend',   () => { composing = false; trigger(); });

  let t = null, controller = null;
  const debounce = (fn, ms=200) => (...args) => { clearTimeout(t); t=setTimeout(() => fn(...args), ms); };

  const trigger = debounce(async () => {
    if (composing) return;
    const raw = tx.value;
    updateCount();
    if (!raw.trim()) { statusEl.textContent=''; preview.textContent=''; updateApplyState(); return; }

    if (controller) controller.abort();
    controller = new AbortController();

    try {
      const res = await fetch(MOD_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrftok
        },
        body: JSON.stringify({ text: raw }),
        signal: controller.signal
      });
      if (!res.ok) return;
      const data = await res.json();
      preview.textContent = data.clean || '';
      statusEl.textContent = data.toxic
        ? '⚠️ Some words will be masked upon publication.'
        : '✓ No issues detected';
      updateApplyState();
    } catch (e) {}
  }, 500);

  tx.addEventListener('input', trigger);
  applyBtn.addEventListener('click', () => {
    const clean = (preview.textContent || '').trim();
    if (!clean) return;
    tx.value = clean; tx.focus();
    tx.selectionStart = tx.selectionEnd = tx.value.length;
    tx.dispatchEvent(new Event('input'));
  });

  /* ====== Uploader (drag & drop, preview) ====== */
  const wrap = document.getElementById('postImageUpload');
  if (!wrap) return;
  const input    = wrap.querySelector('.upload-input');
  const drop     = wrap.querySelector('.upload-drop');
  const choose   = wrap.querySelector('.upload-choose');
  const filename = wrap.querySelector('.upload-filename');
  const previewC = wrap.querySelector('.upload-preview');
  const img      = previewC?.querySelector('img');
  const remove   = wrap.querySelector('.upload-remove');
  const maxBytes = Number(wrap.getAttribute('data-max') || 0);

  function setFilename(name){ filename.textContent = name || filename.dataset.default; }
  function showPreview(url){ if(!img) return; img.src = url; previewC.hidden=false; remove.hidden=false; }
  function clearFile(){
    input.value = '';
    setFilename('');
    previewC.hidden = true;
    if (img) img.src = '';
    remove.hidden = true;
  }

  choose?.addEventListener('click', () => input.click());
  remove?.addEventListener('click', clearFile);

  input.addEventListener('change', () => {
    const file = input.files?.[0];
    if (!file) { clearFile(); return; }
    if (maxBytes && file.size > maxBytes) { alert('File too large (max 2MB).'); clearFile(); return; }
    setFilename(file.name);
    showPreview(URL.createObjectURL(file));
  });

  ['dragenter','dragover'].forEach(ev => drop.addEventListener(ev, e => {
    e.preventDefault(); e.stopPropagation(); drop.classList.add('dragging');
  }));
  ['dragleave','drop'].forEach(ev => drop.addEventListener(ev, e => {
    e.preventDefault(); e.stopPropagation(); drop.classList.remove('dragging');
  }));
  drop.addEventListener('drop', e => {
    const file = e.dataTransfer.files?.[0];
    if (file) {
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      input.dispatchEvent(new Event('change'));
    }
  });
});
</script>
@endsection

@section('styles')
<style>
    .post-title {
    font-weight: 700;
    font-size: 2rem;
    color: #1e293b; /* bleu-gris foncé */
    margin-bottom: 0.25rem;
    }
    .btn-light { background:#f3f4f6; color:#374151; border:1px solid #e5e7eb; }
    .btn-light:hover { background:#e5e7eb; }
    .btn-xs { padding:.25rem .5rem; font-size:.75rem; }

    .forum-container { max-width:800px; margin:2rem auto; padding:0 1rem }
    .forum-header { text-align:center; margin-bottom:2rem }
    .forum-header h1 { color:#2d3748; margin-bottom:.5rem }
    .forum-header p { color:#718096; margin-bottom:1.5rem }

    .btn { display:inline-flex; align-items:center; gap:.5rem; padding:.5rem 1rem; border:none; border-radius:.375rem; text-decoration:none; font-weight:500; cursor:pointer; transition:.2s }
    .btn-primary{ background:#3b82f6; color:#fff } .btn-primary:hover{ background:#2563eb }
    .btn-secondary{ background:#6b7280; color:#fff } .btn-secondary:hover{ background:#4b5563 }

    .post-form-container{ max-width:600px; margin:0 auto; background:#fff; padding:2rem; border-radius:.75rem; box-shadow:0 1px 3px rgba(0,0,0,.1) }
    .form-group{ margin-bottom:1.5rem }
    .form-label{ display:block; margin-bottom:.5rem; font-weight:600; color:#374151 }
    .form-label.required::after{ content:" *"; color:#ef4444 }
    .form-control{ width:100%; padding:.75rem; border:1px solid #d1d5db; border-radius:.375rem; font-size:1rem }
    .form-control:focus{ outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,.1) }
    .char-count{ text-align:right; font-size:.875rem; color:#6b7280; margin-top:.25rem }
    .error-message{ color:#ef4444; font-size:.875rem; margin-top:.25rem }
    .form-actions{ display:flex; gap:1rem; justify-content:flex-end; margin-top:2rem }

    .moderation-preview{ white-space:pre-wrap; margin-top:.25rem; padding:.5rem .75rem; border:1px dashed #e5e7eb; border-radius:.375rem; color:#374151; background:#fafafa; font-size:.95rem }

    /* Uploader */
    .upload-card{border:1px solid #e5e7eb;border-radius:.75rem;padding:.75rem;background:#fff}
    .upload-drop{
      display:flex;gap:.75rem;align-items:center;justify-content:center;
      border:2px dashed #d1d5db;border-radius:.75rem;padding:1rem;background:#f9fafb;cursor:pointer;transition:.2s;
    }
    .upload-drop:hover{background:#f3f4f6;border-color:#cbd5e1}
    .upload-drop.dragging{background:#eef2ff;border-color:#c7d2fe}
    .upload-icon{font-size:1.5rem;color:#3b82f6}
    .upload-text{color:#374151;text-align:center}
    .upload-hint{font-size:.8rem;color:#6b7280;margin-top:.25rem}
    .upload-meta{display:flex;justify-content:space-between;align-items:center;margin-top:.5rem}
    .upload-filename{font-size:.9rem;color:#4b5563}
    .upload-preview{margin-top:.6rem}
    .upload-preview img{display:block;width:100%;max-height:340px;object-fit:cover;border-radius:.5rem;border:1px solid #e5e7eb}
    .btn-ghost{background:#fff;border:1px solid #e5e7eb;color:#374151}
    .btn-ghost:hover{background:#f3f4f6}

    @media (max-width:768px){
      .post-form-container{ padding:1.5rem }
      .form-actions{ flex-direction:column }
      .btn{ width:100%; justify-content:center }
    }
</style>
@endsection
