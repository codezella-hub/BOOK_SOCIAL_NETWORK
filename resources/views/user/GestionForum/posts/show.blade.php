@extends('layouts.user-layout')

@section('title', $post->title)
@section('content')
<div class="forum-container">
    {{-- HEADER --}}
    <div class="forum-header">
        <div class="header-content">
            <h1>Post Details</h1>
            <p>View the complete information of the post</p>
        </div>

        <div class="page-actions" role="toolbar" aria-label="Post actions">
            @if($post->created_by == auth()->id())
                <a href="{{ route('user.posts.edit', $post) }}"
                   class="btn-action btn-edit" title="Edit this post">
                    <i class="fas fa-edit" aria-hidden="true"></i>
                    <span class="label">Edit</span>
                </a>
            @endif

            <a href="{{ route('user.posts.index') }}"
               class="btn-action btn-back" title="Back to posts">
                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                <span class="label">Back</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="post-detail-container">
        {{-- ===== MAIN POST ===== --}}
        <div class="main-post">
            <div class="post-header">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="user-details">
                        <div class="user-top">
                            <h3>{{ $post->user->name ?? 'Utilisateur' }}</h3>
                            <span class="post-time">{{ $post->P_created_at->diffForHumans() }}</span>
                        </div>
                        <span class="topic-badge">
                            <i class="fas fa-bookmark"></i> {{ $post->topic->title }}
                        </span>
                    </div>
                </div>

                <div class="post-header-right">
                    @auth
                        <div class="post-menu">
                            <button class="menu-trigger" onclick="toggleMenu('show-{{ $post->id }}')" aria-label="Open post menu">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" id="menu-show-{{ $post->id }}">
                                @if($post->created_by == auth()->id())
                                    <a href="{{ route('user.posts.edit', $post) }}" class="menu-item">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('user.posts.destroy', $post) }}" method="POST" class="menu-item-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="menu-item delete-btn" >
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                                @if($post->created_by != auth()->id())
                                    <button type="button" class="menu-item report-btn" onclick="reportPost('{{ $post->id }}')">
                                        <i class="fas fa-flag"></i> Report
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="post-content">
                <p>{{ $post->content_P }}</p>
            </div>

            <div class="post-stats">
                <div class="stat-item">
                    <i class="fas fa-heart"></i>
                    <span>{{ $post->likes->count() }} likes</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-comment"></i>
                    <span>{{ $post->comments->count() }} comments</span>
                </div>
            </div>
        </div>

        {{-- ===== COMMENTS ===== --}}
        <div class="comments-section">
            <h3>Comments ({{ $post->comments->count() }})</h3>

            @if($post->comments->count() > 0)
                <div class="comments-list">
                    @foreach($post->comments as $comment)
                        @php
                            $cLikes   = $comment->likes_count ?? ($comment->likes->count() ?? 0);
                            $likedByMe= auth()->check() ? $comment->isLikedBy(auth()->id()) : false;
                        @endphp

                        <div class="comment-item" id="comment-{{ $comment->id }}">
                            <div class="comment-header">
                                <div class="user-info">
                                    <div class="user-avatar-sm">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <h4>{{ $comment->user->name ?? 'Utilisateur' }}</h4>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                @if($comment->created_by == auth()->id())
                                    <div class="post-header-right">
                                        <div class="post-menu">
                                            <button class="menu-trigger" onclick="toggleMenu('comment-{{ $comment->id }}')" aria-label="Open comment menu">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu" id="menu-comment-{{ $comment->id }}">
                                                <a href="{{ route('user.comments.edit', $comment) }}" class="menu-item">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('user.comments.destroy', $comment) }}" method="POST" class="menu-item-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="menu-item delete-btn" onclick="return confirm('Delete this comment?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="comment-content">
                                <p>{{ $comment->content_C }}</p>
                            </div>

                            {{-- Actions: Like + Reply toggle --}}
                            <div class="comment-actions">
                                @auth
                                    <form action="{{ route('user.comment_likes.toggle', $comment) }}"
                                          method="POST"
                                          class="comment-like-form"
                                          data-comment-id="{{ $comment->id }}">
                                        @csrf
                                        <button type="submit" class="comment-like-btn {{ $likedByMe ? 'liked' : '' }}">
                                            <i class="fas fa-heart comment-like-icon"></i>
                                            <span class="comment-like-text">{{ $likedByMe ? 'Unlike' : 'Like' }}</span>
                                            <span class="comment-like-count">({{ $cLikes }})</span>
                                        </button>
                                        <button type="button"
                                                class="comment-reply-btn comment-reply-toggle"
                                                data-target="#reply-form-{{ $comment->id }}"
                                                data-mention="{{ '@'.($comment->user->name ?? 'user') }}">
                                            <i class="fas fa-reply comment-reply-icon"></i>
                                            <span>Reply</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="comment-like-btn">
                                        <i class="fas fa-heart comment-like-icon"></i>
                                        <span class="comment-like-text">Like</span>
                                        <span class="comment-like-count">({{ $cLikes }})</span>
                                    </a>
                                @endauth
                            </div>

                            {{-- REPLIES LIST --}}
                            @if($comment->replies && $comment->replies->count())
                                <div class="replies">
                                    @foreach($comment->replies as $reply)
                                        @php
                                            $rLikes   = $reply->likes_count ?? ($reply->likes->count() ?? 0);
                                            $rLikedBy = auth()->check() ? $reply->isLikedBy(auth()->id()) : false;
                                        @endphp
                                        <div class="reply-item" id="comment-{{ $reply->id }}">
                                            <div class="reply-header">
                                                <div class="user-info">
                                                    <div class="user-avatar-xs"><i class="fas fa-user"></i></div>
                                                    <div class="user-details">
                                                        <h5>{{ $reply->user->name ?? 'Utilisateur' }}</h5>
                                                        <span class="comment-time">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>

                                                @if($reply->created_by == auth()->id())
                                                    <div class="post-header-right">
                                                        <div class="post-menu">
                                                            <button class="menu-trigger" onclick="toggleMenu('comment-{{ $reply->id }}')" aria-label="Open reply menu">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu" id="menu-comment-{{ $reply->id }}">
                                                                <a href="{{ route('user.comments.edit', $reply) }}" class="menu-item">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a>
                                                                <form action="{{ route('user.comments.destroy', $reply) }}" method="POST" class="menu-item-form">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="menu-item delete-btn" onclick="return confirm('Delete this reply?')">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="comment-content">
                                                <p>{{ $reply->content_C }}</p>
                                            </div>

                                            <div class="comment-actions">
                                                @auth
                                                    <form action="{{ route('user.comment_likes.toggle', $reply) }}"
                                                          method="POST"
                                                          class="comment-like-form"
                                                          data-comment-id="{{ $reply->id }}">
                                                        @csrf
                                                        <button type="submit" class="comment-like-btn {{ $rLikedBy ? 'liked' : '' }}">
                                                            <i class="fas fa-heart comment-like-icon"></i>
                                                            <span class="comment-like-text">{{ $rLikedBy ? 'Unlike' : 'Like' }}</span>
                                                            <span class="comment-like-count">({{ $rLikes }})</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('login') }}" class="comment-like-btn">
                                                        <i class="fas fa-heart comment-like-icon"></i>
                                                        <span class="comment-like-text">Like</span>
                                                        <span class="comment-like-count">({{ $rLikes }})</span>
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- REPLY FORM (hidden by default) --}}
                            @auth
                                <form id="reply-form-{{ $comment->id }}"
                                      class="reply-form"
                                      action="{{ route('user.comments.reply.store', $comment) }}"
                                      method="POST">
                                    @csrf
                                    <textarea name="content_C"
                                              rows="2"
                                              class="form-control"
                                              placeholder="Write your reply..."></textarea>
                                    <div class="form-actions reply-actions">
                                        <button type="button" class="btn btn-light btn-xs reply-cancel" data-target="#reply-form-{{ $comment->id }}">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-xs">
                                            <i class="fas fa-paper-plane"></i> Reply
                                        </button>
                                    </div>
                                </form>
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-comments">
                    <i class="fas fa-comments"></i>
                    <p>No comments yet</p>
                    <p>Be the first to comment on this post!</p>
                </div>
            @endif

            {{-- Add top-level comment --}}
            <div class="comment-form" id="comment-form">
                <h4>Add a Comment</h4>

                <form action="{{ route('user.comments.store', $post) }}" method="POST">
                    @csrf

                    <textarea
                        name="content_C"
                        id="content_C"
                        rows="3"
                        class="form-control"
                        placeholder="Write your comment..."
                        required>{{ old('content_C') }}</textarea>

                    <small id="moderation-status-comment" class="text-muted"></small>
                    <div id="moderation-preview-comment" class="moderation-preview" aria-live="polite"></div>
                    <div class="moderation-actions" style="margin-top:.5rem">
                        <button type="button" id="applyCleanComment" class="btn btn-light btn-xs" disabled>
                            Apply cleaned text
                        </button>
                    </div>

                    @error('content_C')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger zone --}}
        @if($post->created_by == auth()->id())
            <div class="danger-zone">
                <h4>Danger Zone</h4>
                <p>Deleting this post is irreversible.</p>
                <form id="dangerDeleteForm" action="{{ route('user.posts.destroy', $post) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-danger" id="dangerDeleteBtn">
                        <i class="fas fa-trash"></i> Delete this post
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

<!-- ===== MODAL DE CONFIRMATION DE SUPPRESSION ===== -->
<div id="deleteConfirmModal" class="delete-modal">
    <div class="delete-modal-content">
        <div class="delete-modal-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
            <button class="close-delete" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="delete-modal-body">
            <p>Are you sure you want to delete this post? This action cannot be undone.</p>
        </div>
        <div class="delete-modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
    </div>
</div>

@section('scripts')
<script>
/* ===== post/comment menu toggle ===== */
function toggleMenu(suffix) {
    const id = `menu-${suffix}`;
    const menu = document.getElementById(id);
    document.querySelectorAll('.dropdown-menu').forEach(m => { if (m !== menu) m.classList.remove('active'); });
    menu?.classList.toggle('active');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.post-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('active'));
    }
});

/* ===== COMMENT LIKE AJAX ===== */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.comment-like-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn   = form.querySelector('.comment-like-btn');
            const icon  = form.querySelector('.comment-like-icon');
            const text  = form.querySelector('.comment-like-text');
            const count = form.querySelector('.comment-like-count');
            btn.disabled = true;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                });
                if (!res.ok) throw new Error('Network error');

                const data = await res.json();
                if (data.success) {
                    if (data.liked) { btn.classList.add('liked'); text.textContent = 'Unlike'; }
                    else { btn.classList.remove('liked'); text.textContent = 'Like'; }
                    count.textContent = `(${data.likes_count})`;
                    icon.style.transform = 'scale(1.2)';
                    setTimeout(() => icon.style.transform = 'scale(1)', 180);
                }
            } catch (err) {
                console.error(err);
                alert('An error occurred while liking this comment.');
            } finally {
                btn.disabled = false;
            }
        });
    });

    // set initial heart color on liked buttons
    document.querySelectorAll('.comment-like-btn.liked .comment-like-icon')
        .forEach(i => i.style.color = '#ef4444');
});

/* ===== REPLY TOGGLE + PREFILL @mention ===== */
document.addEventListener('click', (e)=>{
  const toggle = e.target.closest('.comment-reply-toggle');
  if (toggle) {
    const targetSel = toggle.getAttribute('data-target');
    const form = document.querySelector(targetSel);
    if (!form) return;
    form.classList.toggle('active');
    
    toggle.classList.toggle('active', form.classList.contains('active'));
    // Prefill with @mention if opening
    const mention = toggle.getAttribute('data-mention') || '';
    const tx = form.querySelector('textarea');
    if (form.classList.contains('active') && tx && !tx.value.trim() && mention) {
        tx.value = mention + ' ';
        tx.focus();
        // place cursor at end
        const v = tx.value; tx.value=''; tx.value=v;
    }
  }

  // Cancel button inside reply form
  const cancel = e.target.closest('.reply-cancel');
  if (cancel) {
    const targetSel = cancel.getAttribute('data-target');
    const form = document.querySelector(targetSel);
    form?.classList.remove('active');

    // >>> et on retire aussi l’état actif du bouton Reply correspondant
    if (form) {
      const btn = document.querySelector(`[data-target="#${form.id}"].comment-reply-toggle`);
      btn?.classList.remove('active');
    }
  }
});

/* ===== OPTIONAL: live moderation for top-level comment only ===== */
document.addEventListener('DOMContentLoaded', () => {
  const tx = document.getElementById('content_C');
  if (!tx) return;

  const MOD_URL  = "{{ route('moderate.live') }}";
  const statusEl = document.getElementById('moderation-status-comment');
  const preview  = document.getElementById('moderation-preview-comment');
  const applyBtn = document.getElementById('applyCleanComment');
  const csrftok  = document.querySelector('meta[name="csrf-token"]').content;

  let composing=false; tx.addEventListener('compositionstart',()=>composing=true);
  tx.addEventListener('compositionend',()=>{composing=false; trigger();});

  let t=null, controller=null;
  const debounce=(fn,ms=300)=>(...a)=>{clearTimeout(t); t=setTimeout(()=>fn(...a),ms);};

  const trigger = debounce(async ()=>{
    if (composing) return;
    const raw = tx.value;
    if(!raw.trim()){ statusEl.textContent=''; preview.textContent=''; applyBtn.disabled=true; return; }

    if (controller) controller.abort(); controller = new AbortController();
    try{
      const res = await fetch(MOD_URL,{
        method:'POST',
        headers:{'Content-Type':'application/json','Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrftok},
        body:JSON.stringify({text:raw}),
        signal:controller.signal
      });
      if(!res.ok) return;
      const data = await res.json();
      preview.textContent = data.clean || '';
      statusEl.textContent = data.toxic ? '⚠️ Some words will be masked upon publication.' : '✓ No issues detected';
      applyBtn.disabled = !(data.clean && data.clean.trim() && data.clean !== raw);
    }catch(e){}
  },400);

  tx.addEventListener('input', trigger);
  applyBtn?.addEventListener('click',()=>{
    const clean=(preview.textContent||'').trim(); if(!clean) return;
    tx.value=clean; tx.dispatchEvent(new Event('input')); tx.focus();
  });
});
// ===== MODAL DE CONFIRMATION SUPPRESSION =====
let deleteForm = null;

document.querySelectorAll('.menu-item-form').forEach(form => {
    const deleteBtn = form.querySelector('.delete-btn');
    deleteBtn.addEventListener('click', e => {
        e.preventDefault();
        deleteForm = form;
        openDeleteModal();
    });
});

function openDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteForm = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
    if (deleteForm) deleteForm.submit();
});

// ===== DANGER ZONE DELETE BUTTON =====
const dangerDeleteBtn = document.getElementById('dangerDeleteBtn');
const dangerDeleteForm = document.getElementById('dangerDeleteForm');

if (dangerDeleteBtn) {
    dangerDeleteBtn.addEventListener('click', e => {
        e.preventDefault();
        deleteForm = dangerDeleteForm; // reuse the global deleteForm variable
        openDeleteModal();
    });
}

</script>
@endsection

@section('styles')
<style>
/* ===== container/header ===== */
.forum-container{max-width:800px;margin:2rem auto;padding:0 1rem}
.forum-header{text-align:center;margin-bottom:2rem}
.forum-header h1{color:#2d3748;margin-bottom:.5rem}
.forum-header p{color:#718096;margin-bottom:1rem}

/* alert */
.alert{padding:1rem;border-radius:.375rem;margin:1rem 0;display:flex;gap:.5rem;align-items:center}
.alert-success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0}

/* ===== main card ===== */
.post-detail-container{max-width:800px;margin:0 auto}
.main-post{background:#fff;border-radius:.75rem;padding:1.5rem;margin-bottom:2rem;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid #e2e8f0}
.post-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem}

/* user */
.user-info{display:flex;gap:.9rem;align-items:center}
.user-avatar{width:40px;height:40px;background:black;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff}
.user-avatar-sm{width:40px;height:40px;background:#6b7280;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff}
.user-avatar-xs{width:32px;height:32px;background:#9ca3af;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem}
.user-details{display:flex;flex-direction:column}
.user-top{display:flex;align-items:center;gap:.35rem}
.user-top h3{margin:0;color:#111827;font-size:1.05rem;font-weight:700}
.post-time{color:#9ca3af;font-size:.875rem}
.post-time::before{content:"•"; margin:0 .35rem 0 .15rem; color:#9ca3af; font-weight:700}

/* topic pill */
.topic-badge{display:inline-flex;align-items:center;gap:.35rem;background:#eef2ff;color:#3730a3;border:1px solid #e0e7ff;padding:.25rem .6rem;border-radius:9999px;font-size:.75rem;font-weight:600;width:auto;max-width:max-content;white-space:nowrap;margin-top:.25rem}
.topic-badge i{font-size:.8rem}

/* menu */
.post-header-right{display:flex;align-items:center;gap:.5rem}
.post-menu{position:relative}
.menu-trigger{display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;color:#64748b;padding:0;cursor:pointer;transition:.2s;box-shadow:0 1px 2px rgba(0,0,0,.04)}
.menu-trigger .fa-ellipsis-v{transform:rotate(90deg)}
.menu-trigger:hover,.menu-trigger:focus{background:#f5f7ff;border-color:#c7d2fe;color:#374151;box-shadow:0 2px 6px rgba(0,0,0,.08);outline:none}
.dropdown-menu{position:absolute;top:100%;right:0;background:#fff;border:1px solid #e2e8f0;border-radius:.5rem;box-shadow:0 4px 6px -1px rgba(0,0,0,.1),0 2px 4px -1px rgba(0,0,0,.06);min-width:160px;z-index:1000;opacity:0;visibility:hidden;transform:translateY(-10px);transition:.2s}
.dropdown-menu.active{opacity:1;visibility:visible;transform:translateY(0)}
.menu-item{display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;text-decoration:none;color:#374151;font-size:.875rem;border:none;background:none;width:100%;text-align:left;cursor:pointer}
.menu-item:hover{background:#f8fafc}
.menu-item-form{width:100%}
.delete-btn{color:#ef4444}
.report-btn{color:#f59e0b}
.report-btn:hover{background:#fffbeb}

/* content */
.post-content{margin-bottom:1rem}
.post-content p{color:#4a5568;line-height:1.7;margin:0}

/* stats */
.post-stats{display:flex;gap:1.5rem;margin-top:.75rem;padding:.75rem 0;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9}
.stat-item{display:flex;align-items:center;gap:.5rem;color:#64748b;font-size:.875rem;font-weight:500}
.stat-item .fa-heart{color:#ef4444}
.stat-item .fa-comment{color:#06b6d4}

/* ===== comments ===== */
.comments-section{background:#fff;border-radius:.75rem;padding:1.5rem;box-shadow:0 1px 3px rgba(0,0,0,.08);border:1px solid #e2e8f0;margin-bottom:2rem}
.comments-section h3{color:#2d3748;margin-bottom:1rem;font-size:1.1rem}
.comments-list{display:flex;flex-direction:column;gap:1rem;margin-bottom:1.5rem}
.comment-item{padding:1rem;border:1px solid #f1f5f9;border-radius:.5rem;background:#f8fafc}
.comment-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.5rem}
.comment-content p{color:#4a5568;line-height:1.6;margin:0}
.comment-time{color:#9ca3af;font-size:.85rem}
.comment-time::before{content:"•"; margin:0 .35rem 0 .25rem; color:#9ca3af; font-weight:700}

/* comment actions */
.comment-actions{display:flex;align-items:center;gap:.5rem;margin-top:.6rem;flex-wrap:wrap}
.comment-like-btn{
  display:inline-flex;align-items:center;gap:.4rem;
  border:1px solid #e5e7eb;background:#fff;color:#4b5563;
  padding:.35rem .6rem;border-radius:.375rem;cursor:pointer;
  transition:.2s;text-decoration:none;font-size:.85rem;font-weight:600;
}
.comment-like-btn:hover{background:#f3f4f6;border-color:#d1d5db}
.comment-like-btn .comment-like-icon{transition:.2s}
.comment-like-btn.liked{color:#b91c1c;border-color:#fecaca;background:#fff5f5}
.comment-like-btn.liked .comment-like-icon{color:#ef4444}

/* replies */
.replies{margin-top:.6rem;border-left:3px solid #e5e7eb;padding-left:.75rem;display:flex;flex-direction:column;gap:.6rem}
.reply-item{background:#fff;border:1px solid #eef2f7;border-radius:.5rem;padding:.65rem}
.reply-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:.35rem}

/* reply form */
.reply-form{display:none;margin-top:.6rem}
.reply-form.active{display:block}
.reply-actions{display:flex;gap:.5rem;justify-content:flex-end;margin-top:.35rem}

.comment-form{border-top:1px solid #e2e8f0;padding-top:1rem}
.form-control{width:100%;padding:.75rem;border:1px solid #d1d5db;border-radius:.375rem;font-size:1rem;resize:vertical}
.form-control:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.form-actions{display:flex;justify-content:flex-end;margin-top:.75rem}
.error-message{color:#ef4444;font-size:.875rem;margin-top:.25rem}
.moderation-preview{white-space:pre-wrap;margin-top:.25rem;padding:.5rem .75rem;border:1px dashed #e5e7eb;border-radius:.375rem;color:#374151;background:#fafafa;font-size:.95rem}

/* danger zone */
.danger-zone{border-top:1px solid #fecaca;padding-top:1.25rem}
.danger-zone h4{color:#dc2626;margin-bottom:.25rem}
.danger-zone p{color:#7f1d1d;margin-bottom:.75rem;font-size:.875rem}

/* Page actions */
.page-actions{
  display:inline-flex;gap:.5rem;background:#fff;border:1px solid #e5e7eb;
  box-shadow:0 6px 16px rgba(15,23,42,.06);border-radius:14px;padding:.35rem;margin:.75rem auto 0;
}
.btn-action{--h:40px;height:var(--h);min-width:120px;padding:0 .9rem;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;border-radius:10px;border:1px solid transparent;font-weight:600;text-decoration:none;transition:transform .15s, box-shadow .15s, background .15s, border-color .15s;line-height:1}
.btn-edit{background:linear-gradient(180deg,#fde68a,#f59e0b1a);color:#92400e;border-color:#fcd34d}
.btn-edit:hover{background:#fbbf24;color:#fff;border-color:#f59e0b;box-shadow:0 8px 18px rgba(245,158,11,.25);transform:translateY(-1px)}
.btn-back{background:linear-gradient(180deg,#f3f4f6,#e5e7eb);color:#374151;border-color:#e5e7eb}
.btn-back:hover{background:#374151;color:#fff;border-color:#374151;box-shadow:0 8px 18px rgba(55,65,81,.18);transform:translateY(-1px)}
.btn-action i{font-size:1rem}
.btn-action:focus{outline:3px solid transparent;box-shadow:0 0 0 4px rgba(37,99,235,.25)}
.forum-header{text-align:center}
.forum-header .page-actions{display:inline-flex}

/* responsive */
@media (max-width:768px){
  .forum-container{margin:1rem auto;padding:0 .5rem}
  .main-post,.comments-section{padding:1.2rem}
}
@media (max-width:520px){
  .btn-action{min-width:44px;padding:0 .65rem}
  .btn-action .label{display:none}
  .page-actions{padding:.3rem;gap:.35rem}
}
.comment-reply-btn{
  display:inline-flex;align-items:center;gap:.4rem;
  border:1px solid #e5e7eb;background:#fff;color:#4b5563;
  padding:.35rem .6rem;border-radius:.375rem;cursor:pointer;
  transition:.2s;text-decoration:none;font-size:.85rem;font-weight:600;
}
.comment-reply-btn:hover{background:#f3f4f6;border-color:#d1d5db}
.comment-reply-btn .comment-reply-icon{transition:.2s}


.comment-reply-btn.active{
  color:#1d4ed8;                 
  border-color:#bfdbfe;
  background:#eff6ff;
}
.comment-reply-btn.active .comment-reply-icon{color:#2563eb}
/* ===== Modal suppression ===== */
.delete-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .5);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.delete-modal-content {
    background: #fff;
    border-radius: 12px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    animation: fadeIn .25s ease;
    overflow: hidden;
}

.delete-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #eee;
}

.delete-modal-header h3 {
    margin: 0;
    color: #b91c1c;
    font-weight: 700;
    font-size: 1.1rem;
}

.close-delete {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #6b7280;
    cursor: pointer;
}

.close-delete:hover {
    color: #111827;
}

.delete-modal-body {
    padding: 1.25rem;
    color: #374151;
    font-size: .95rem;
}

.delete-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: .75rem;
    padding: 1rem 1.25rem;
    border-top: 1px solid #eee;
}

.btn-danger {
    background: #dc2626;
    color: #fff;
}

.btn-danger:hover {
    background: #b91c1c;
}

.btn-secondary {
    background: #e5e7eb;
    color: #111827;
}

.btn-secondary:hover {
    background: #d1d5db;
}

@keyframes fadeIn {
    from {opacity: 0; transform: scale(.95);}
    to {opacity: 1; transform: scale(1);}
}

</style>
@endsection
