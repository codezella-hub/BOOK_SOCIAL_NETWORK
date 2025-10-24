<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="event-form">
    @csrf
    @if(($method ?? 'POST') !== 'POST') @method($method) @endif

    {{-- Global validation errors --}}
    @if ($errors->any())
        <div class="ef-section ef-col-span" style="margin:12px 0;">
            <div class="ef-error" style="font-weight:700;margin-bottom:6px;">Please fix the following:</div>
            <ul class="ef-hint" style="color:#b91c1c; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ef-card">
        <div class="ef-header">
            <h2 class="ef-title">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h2>
            <p class="ef-sub">Set title, schedule, capacity and visibility. Upload a banner for a nicer listing.</p>
        </div>

        {{-- Basics --}}
        <div class="ef-section">
            <h3 class="ef-section-title">Basics</h3>
            <div class="ef-grid">
                <label class="ef-field">
                    <span class="ef-label">Title</span>
                    <input class="ef-input" type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required>
                    @error('title')<span class="ef-error">{{ $message }}</span>@enderror
                </label>

                <label class="ef-field">
                    <span class="ef-label">Slug</span>
                    <input class="ef-input" type="text" name="slug" value="{{ old('slug', $event->slug ?? '') }}" required>
                    <small class="ef-hint">Used in the URL. Keep it short and unique.</small>
                    @error('slug')<span class="ef-error">{{ $message }}</span>@enderror
                </label>

                <label class="ef-field ef-col-span">
                    <span class="ef-label">Summary</span>
                    <input class="ef-input" type="text" name="summary" value="{{ old('summary', $event->summary ?? '') }}" placeholder="One-line teaser (optional)">
                </label>

                <label class="ef-field ef-col-span">
                    <span class="ef-label">Description</span>
                    <textarea class="ef-textarea" name="description" rows="6" placeholder="What will happen at this event?">{{ old('description', $event->description ?? '') }}</textarea>
                </label>
            </div>
        </div>

        {{-- Schedule & Place --}}
        <div class="ef-section">
            <h3 class="ef-section-title">Schedule & Place</h3>
            <div class="ef-grid">
                <label class="ef-field">
                    <span class="ef-label">Starts at</span>
                    <input class="ef-input" type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($event)?$event->starts_at->format('Y-m-d\TH:i'):'') }}" required>
                </label>

                <label class="ef-field">
                    <span class="ef-label">Ends at</span>
                    <input class="ef-input" type="datetime-local" name="ends_at" value="{{ old('ends_at', isset($event)?$event->ends_at->format('Y-m-d\TH:i'):'') }}" required>
                </label>

                <label class="ef-field">
                    <span class="ef-label">Timezone</span>
                    <input class="ef-input" type="text" name="timezone" value="{{ old('timezone', $event->timezone ?? 'UTC') }}" required>
                    <small class="ef-hint">e.g. UTC, Europe/Paris</small>
                </label>

                <label class="ef-field">
                    <span class="ef-label">Location</span>
                    <input class="ef-input" type="text" name="location_text" value="{{ old('location_text', $event->location_text ?? '') }}" placeholder="Address or 'Online'">
                </label>
                <label class="ef-field">
  <span class="ef-label">Latitude</span>
  <input class="ef-input" type="text" name="lat" value="{{ old('lat', $event->lat ?? '') }}" placeholder="e.g. 36.860000">
  @error('lat')<span class="ef-error">{{ $message }}</span>@enderror
</label>

<label class="ef-field">
  <span class="ef-label">Longitude</span>
  <input class="ef-input" type="text" name="lng" value="{{ old('lng', $event->lng ?? '') }}" placeholder="e.g. 10.200000">
  @error('lng')<span class="ef-error">{{ $message }}</span>@enderror
</label>

<div class="ef-col-span">
  <button type="button"
    class="ef-btn ef-btn-light"
    onclick="document.querySelector('[name=lat]').value='36.86';document.querySelector('[name=lng]').value='10.20';">
    Use Charguia 2 defaults
  </button>
</div>

                
            </div>
        </div>

        {{-- Rules --}}
        <div class="ef-section">
            <h3 class="ef-section-title">Rules</h3>
            <div class="ef-grid">
                <label class="ef-field">
                    <span class="ef-label">Visibility</span>
                    <select class="ef-select" name="visibility">
                        @foreach(['public','private'] as $v)
                            <option value="{{ $v }}" @selected(old('visibility', $event->visibility ?? 'public')===$v)>{{ ucfirst($v) }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="ef-field">
                    <span class="ef-label">Capacity</span>
                    <input class="ef-input" type="number" min="0" name="capacity" value="{{ old('capacity', $event->capacity ?? '') }}" placeholder="Leave empty for unlimited">
                </label>
            </div>
        </div>

        {{-- Media --}}
        <div class="ef-section">
            <h3 class="ef-section-title">Media</h3>
            <div class="ef-grid">
                <label class="ef-field ef-col-span">
                    <span class="ef-label">Cover Image</span>
                    <input class="ef-file" type="file" name="cover_image" accept="image/*">
                    <small class="ef-hint">Recommended 1200×600 JPG/PNG, under 2 MB.</small>
                </label>

                @isset($event->cover_image_path)
                    <div class="ef-preview">
                        <img src="{{ Storage::url($event->cover_image_path) }}" alt="Cover" />
                    </div>
                @endisset
            </div>
        </div>
    </div>

    {{-- Sticky actions --}}
    <div class="ef-actions">
        <button class="ef-btn ef-btn-primary" type="submit">Save</button>
        @isset($event)
            <a class="ef-btn ef-btn-light" href="{{ route('admin.events.participants', $event) }}">Participants</a>
        @endisset
    </div>
</form>

{{-- Scoped CSS --}}
<style>
/* shell */
.event-form { max-width: 72rem; }
.ef-card { background:#fff; border:1px solid #eaeaea; border-radius:16px; box-shadow:0 10px 24px rgba(5,7,9,.05); overflow:hidden; }
.ef-header { padding:20px 24px 10px; border-bottom:1px solid #eee; }
.ef-title { margin:0; font-size:20px; font-weight:800; color:#0f172a; }
.ef-sub { margin:.25rem 0 0; color:#6b7280; font-size:.95rem; }

/* sections */
.ef-section { padding:16px 24px; }
.ef-section + .ef-section { border-top:1px solid #f1f1f1; }
.ef-section-title { margin:0 0 10px; font-size:14px; font-weight:700; letter-spacing:.02em; color:#374151; text-transform:uppercase; }

.ef-grid { display:grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap:14px; }
.ef-col-span { grid-column: span 2; }

/* fields */
.ef-field { display:grid; gap:6px; }
.ef-label { font-size:.9rem; color:#374151; font-weight:600; }
.ef-input, .ef-textarea, .ef-select, .ef-file {
  display:block; width:100%; border:1px solid #e5e7eb; border-radius:10px;
  padding:.65rem .8rem; font-size:.95rem; color:#111827; background:#fff;
  transition:border-color .2s, box-shadow .2s, transform .05s ease;
}
.ef-textarea { min-height:140px; line-height:1.45; }
.ef-input:focus, .ef-textarea:focus, .ef-select:focus {
  outline:none; border-color:#c7d2fe; box-shadow:0 0 0 4px rgba(99,102,241,.15);
}
.ef-input:hover, .ef-textarea:hover, .ef-select:hover { border-color:#d1d5db; }
.ef-input:active, .ef-textarea:active { transform: translateY(0.5px); }

.ef-hint { color:#6b7280; font-size:.8rem; }
.ef-error { color:#dc2626; font-size:.85rem; }

/* media preview */
.ef-preview { grid-column: span 2; border:1px dashed #e5e7eb; border-radius:12px; padding:10px; background:#fafafa; }
.ef-preview img { display:block; width:100%; max-height:220px; object-fit:cover; border-radius:8px; }

/* actions */
.ef-actions {
  position: sticky; bottom: 14px; display:flex; gap:10px; padding:10px;
  background: rgba(255,255,255,.8); backdrop-filter: blur(8px);
  border:1px solid #eaeaea; border-radius:14px; margin-top:14px; box-shadow:0 10px 24px rgba(5,7,9,.08);
}
.ef-btn { appearance:none; border:1px solid #e5e7eb; background:#f8fafc; color:#111827; padding:.6rem 1rem; border-radius:10px; font-weight:700; font-size:.95rem; cursor:pointer; transition: all .2s; }
.ef-btn:hover { background:#f1f5f9; }
.ef-btn-primary { background:#4f46e5; border-color:#4f46e5; color:#fff; box-shadow:0 8px 18px rgba(79,70,229,.25); }
.ef-btn-primary:hover { background:#4338ca; }
.ef-btn-light { background:#fff; }

/* responsive */
@media (max-width: 800px) {
  .ef-grid { grid-template-columns: 1fr; }
  .ef-col-span, .ef-preview { grid-column: span 1; }
}
</style>
