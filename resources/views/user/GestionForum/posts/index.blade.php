@extends('layouts.user-layout')

@section('title', 'Forum - Posts')
@section('content')
<div class="forum-container">
    {{-- HERO (simple, n’interfère pas avec le header) --}}
    <div class="forum-hero">
        <div class="hero-text">
            <h1 class="post-title">Forum Discussions</h1>
            <p>Share your thoughts and discuss with the community</p>
        </div>
        <a href="{{ route('user.posts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            New Post
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- BARRE DE FILTRES (non-sticky pour ne pas recouvrir le header/profile) --}}
    <form method="GET" action="{{ route('user.posts.index') }}" class="filters-card">
        <div class="filters-row">
            {{-- Topic --}}
            <label class="filter">
                <span>Topic</span>
                <div class="select-wrap">
                    <i class="fas fa-tag"></i>
                    <select name="topic" onchange="this.form.submit()">
                        <option value="">All topics</option>
                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" {{ (string)request('topic') === (string)$t->id ? 'selected' : '' }}>
                                {{ $t->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </label>

            {{-- Sort --}}
            <label class="filter">
                <span>Sort</span>
                <div class="select-wrap">
                    <i class="fas fa-sort-amount-down"></i>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort','newest')==='newest' ? 'selected' : '' }}>Newest first</option>
                        <option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Oldest first</option>
                    </select>
                </div>
            </label>

            {{-- Fallback submit --}}
            <button class="btn btn-primary filters-submit" type="submit">
                <i class="fas fa-filter"></i> Apply
            </button>

            {{-- Clear --}}
            @if(request()->has('topic') || request()->has('sort'))
                <a href="{{ route('user.posts.index') }}" class="btn btn-ghost">
                    <i class="fas fa-times"></i> Clear
                </a>
            @endif
        </div>

        {{-- Chips actifs --}}
        <div class="filters-active">
            @if(request('topic'))
                <span class="chip"><i class="fas fa-tag"></i> {{ optional($topics->firstWhere('id', request('topic')))->title }}</span>
            @endif
            @if(request()->filled('sort'))
                <span class="chip"><i class="fas fa-clock"></i> {{ request('sort')==='oldest' ? 'Oldest first' : 'Newest first' }}</span>
            @endif
        </div>
    </form>

    {{-- POSTS --}}
    <div class="posts-list">
        @forelse($posts as $post)
            <div class="post-card">
                <div class="post-header">
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-details">
                            {{-- nom + petit point + temps --}}
                            <div class="user-top">
                                <h4>{{ $post->user->name ?? 'Utilisateur' }}</h4>
                                <span class="post-time">{{ $post->P_created_at->diffForHumans() }}</span>
                            </div>
                            {{-- topic en pill avec icône --}}
                            <span class="topic-badge">
                                <i class="fas fa-bookmark"></i> {{ $post->topic->title }}
                            </span>
                        </div>
                    </div>

                    <div class="post-header-right">
                        @auth
                            <div class="post-menu">
                                <button class="menu-trigger" onclick="toggleMenu('{{ $post->id }}')" aria-label="Open post menu">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" id="menu-{{ $post->id }}">
                                    <a href="{{ route('user.posts.show', $post) }}" class="menu-item">
                                        <i class="fas fa-eye"></i>
                                        Show More
                                    </a>
                                    @if($post->created_by == auth()->user()->id)
                                        <a href="{{ route('user.posts.edit', $post) }}" class="menu-item">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('user.posts.destroy', $post) }}" method="POST" class="menu-item-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="menu-item delete-btn" >
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                    @if($post->created_by != auth()->user()->id)
                                        <button type="button" class="menu-item report-btn" onclick="reportPost('{{ $post->id }}')">
                                            <i class="fas fa-flag"></i>
                                            Report
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="post-content">
                    <p class="content-text">{{ $post->content_P }}</p>
                    <a href="{{ route('user.posts.show', $post) }}" class="read-more">→ Read more</a>

                    {{-- IMAGE DU POST (sous Read more) --}}
                    @php
                        $imgUrl = null;
                        if (!empty($post->image_path) && Storage::disk('public')->exists($post->image_path)) {
                            $imgUrl = Storage::url($post->image_path); // => /storage/...
                        }
                    @endphp

                    @if($imgUrl)
                        <div class="post-image">
                            <img src="{{ $imgUrl }}" alt="Post image" loading="lazy"
                                onerror="this.closest('.post-image')?.remove();">
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
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

                {{-- Boutons --}}
                <div class="post-action-buttons">
                    @auth
                        <form action="{{ route('user.likes.toggle', $post) }}" method="POST" class="like-form">
                            @csrf
                            <button type="submit" class="action-btn like-btn"
                                    data-post-id="{{ $post->id }}"
                                    data-liked="{{ $post->likes->contains('liked_by', auth()->user()->id) ? 'true' : 'false' }}">
                                <i class="fas fa-heart like-icon {{ $post->likes->contains('liked_by', auth()->user()->id) ? 'liked' : '' }}"></i>
                                <span class="action-text">
                                    {{ $post->likes->contains('liked_by', auth()->user()->id) ? 'Unlike' : 'Like' }}
                                </span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="action-btn like-btn">
                            <i class="fas fa-heart like-icon"></i>
                            <span class="action-text">Like</span>
                        </a>
                    @endauth

                    <a href="{{ route('user.posts.show', $post) }}" class="action-btn comment-btn">
                        <i class="fas fa-comment comment-icon"></i>
                        <span class="action-text">Comment</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-comments empty-icon"></i>
                <h3>No posts found</h3>
                <p>Be the first to share your thoughts!</p>
                <a href="{{ route('user.posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Create a post
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($posts instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="pagination-wrap">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

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
function toggleMenu(postId) {
    const menu = document.getElementById(`menu-${postId}`);
    const allMenus = document.querySelectorAll('.dropdown-menu');
    allMenus.forEach(m => { if (m !== menu) m.classList.remove('active'); });
    menu.classList.toggle('active');
}
document.addEventListener('click', e => {
    if (!e.target.closest('.post-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('active'));
    }
});

// ===== REPORT SYSTEM (identique à ton implémentation) =====
let currentPostId = null;
function reportPost(postId){ currentPostId=postId; openReportModal(); }
function openReportModal(){ const m=document.getElementById('reportModal'); m.style.display='flex'; document.body.style.overflow='hidden'; resetReportForm(); }
function closeReportModal(){ const m=document.getElementById('reportModal'); m.style.display='none'; document.body.style.overflow='auto'; currentPostId=null; }
function resetReportForm(){ document.querySelectorAll('input[name="reportReason"]').forEach(r=>r.checked=false); document.getElementById('reportDetails').value=''; document.getElementById('additionalInfo').style.display='none'; document.getElementById('submitReportBtn').disabled=true; }
function submitReport(){
    const selected=document.querySelector('input[name="reportReason"]:checked');
    const details=document.getElementById('reportDetails').value;
    if(!selected){ alert('Please select a reason'); return; }
    const btn=document.getElementById('submitReportBtn'), text=btn.querySelector('.btn-text'), load=btn.querySelector('.btn-loading');
    text.style.display='none'; load.style.display='flex'; btn.disabled=true;
    fetch(`/forum/posts/${currentPostId}/report`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
        body:JSON.stringify({reason:selected.value,details})
    }).then(r=>r.json()).then(d=>{
        if(d.success){ closeReportModal(); showConfirmation(); }
        else{ alert(d.message||'Error submitting report'); text.style.display='block'; load.style.display='none'; btn.disabled=false; }
    }).catch(()=>{ alert('Error submitting report'); text.style.display='block'; load.style.display='none'; btn.disabled=false; });
}
function showConfirmation(){ document.getElementById('reportConfirmation').style.display='flex'; }
function closeConfirmation(){ document.getElementById('reportConfirmation').style.display='none'; }

// ===== LIKE SYSTEM =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-form').forEach(form=>{
        form.addEventListener('submit',e=>{
            e.preventDefault();
            const button=form.querySelector('.like-btn');
            const likeIcon=form.querySelector('.like-icon');
            const likeText=form.querySelector('.action-text');
            const statsLikes=form.closest('.post-card').querySelector('.stat-item:first-child span');
            button.disabled=true;

            fetch(form.action,{
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json','X-Requested-With':'XMLHttpRequest'},
                body:JSON.stringify({})
            })
            .then(r=>r.json())
            .then(data=>{
                if(data.success){
                    if(data.liked){ likeIcon.style.color='#ef4444'; likeIcon.classList.add('liked'); likeText.textContent='Unlike'; }
                    else{ likeIcon.style.color='#6b7280'; likeIcon.classList.remove('liked'); likeText.textContent='Like'; }
                    if (statsLikes) statsLikes.textContent = `${data.likes_count} likes`;
                    likeIcon.style.transform='scale(1.3)';
                    setTimeout(()=>likeIcon.style.transform='scale(1)',300);
                }
            })
            .finally(()=>button.disabled=false);
        });
    });

    document.querySelectorAll('.like-icon.liked').forEach(i=>i.style.color='#ef4444');

    document.querySelectorAll('input[name="reportReason"]').forEach(r=>{
        r.addEventListener('change',function(){
            document.getElementById('submitReportBtn').disabled=!this.checked;
            document.getElementById('additionalInfo').style.display=this.checked?'block':'none';
        });
    });

    document.getElementById('reportModal')?.addEventListener('click',e=>{ if(e.target===e.currentTarget) closeReportModal(); });
    document.getElementById('reportConfirmation')?.addEventListener('click',e=>{ if(e.target===e.currentTarget) closeConfirmation(); });
});

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
</script>
@endsection

@section('styles')
<style>
    :root{
        --bg:#f7f8fc;
        --card:#ffffff;
        --text:#1f2937;
        --muted:#6b7280;
        --border:#e5e7eb;
        --brand:#3b82f6;
        --brand-600:#2563eb;
        --chip:#eef2ff;
        --chip-text:#3730a3;
        --accent:#06b6d4;
        --danger:#ef4444;
        --shadow:0 10px 25px rgba(15,23,42,.06);
        --shadow-hover:0 16px 32px rgba(15,23,42,.12);
    }

    .forum-container{max-width:900px;margin:2rem auto;padding:0 1rem}
    body{background:var(--bg)}

    /* HERO */
    .forum-hero{
        background:linear-gradient(135deg,#f0f5ff,#fff);
        border:1px solid var(--border);
        border-radius:18px;
        padding:1.25rem 1.25rem 1rem;
        margin-bottom:1rem;
        display:flex;gap:1rem;align-items:center;justify-content:space-between;
        box-shadow:var(--shadow);
    }
    .hero-text h1{margin:0;color:#111827;font-weight:800;letter-spacing:.3px}
    .hero-text p{margin:.25rem 0 0;color:var(--muted)}
    .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.6rem 1rem;border:none;border-radius:10px;font-weight:600;cursor:pointer;text-decoration:none;transition:.2s}
    .btn-primary{background:var(--brand);color:#fff;box-shadow:0 6px 14px rgba(37,99,235,.18)}
    .btn-primary:hover{background:var(--brand-600);transform:translateY(-1px)}
    .btn-ghost{background:#fff;color:#374151;border:1px solid var(--border)}
    .btn-ghost:hover{background:#f8fafc}

    .alert{padding:1rem;border-radius:12px;margin:1rem 0;display:flex;gap:.6rem;align-items:center}
    .alert-success{background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46}

    /* FILTERS (non-sticky) */
    .filters-card{background:var(--card);border:1px solid var(--border);
        border-radius:14px;padding:.9rem 1rem;margin:1rem 0 1.25rem;box-shadow:var(--shadow)}
    .filters-row{display:flex;gap:.75rem;align-items:end;flex-wrap:wrap}
    .filter{display:flex;flex-direction:column;gap:.4rem}
    .filter span{font-size:.85rem;color:#374151;font-weight:700}
    .select-wrap{display:flex;align-items:center;gap:.5rem;background:#fff;border:1px solid var(--border);border-radius:10px;padding:.45rem .6rem;min-width:220px}
    .select-wrap i{color:var(--muted)}
    .filter select{appearance:none;border:none;outline:none;background:transparent;width:100%;color:#374151}
    .filters-submit{height:40px}
    .filters-active{margin-top:.5rem;display:flex;gap:.5rem;flex-wrap:wrap}
    .chip{background:var(--chip);color:var(--chip-text);padding:.25rem .6rem;border-radius:999px;font-size:.8rem;display:inline-flex;gap:.35rem;align-items:center}

    /* POSTS */
    .posts-list{display:flex;flex-direction:column;gap:1rem}
    .post-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:1.1rem 1.1rem .9rem;box-shadow:var(--shadow);transition:.2s}
    .post-card:hover{box-shadow:var(--shadow-hover);transform:translateY(-1px)}

    .post-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem}
    .user-info{display:flex;gap:.85rem;align-items:center}
    .user-avatar{width:40px;height:40px;background:black;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff}

    .user-details{display:flex;flex-direction:column}
    .user-top{display:flex;align-items:center;gap:.25rem}
    .user-top h4{margin:0;font-weight:600;color:#111827}
    .post-time{color:#9ca3af;font-size:.875rem}
    .post-time::before{content:"•"; margin:0 .35rem 0 .25rem; color:#9ca3af; font-weight:700}

    .post-header-right{display:flex;align-items:center;gap:.5rem}

    .topic-badge{display:inline-flex;align-items:center; gap:.35rem; align-self:flex-start; width:auto; max-width:max-content; white-space:nowrap; line-height:1;background:#eef2ff;color:#3730a3;border:1px solid #e0e7ff;padding:.25rem .6rem;border-radius:9999px;font-size:.75rem;font-weight:600;margin-top:.25rem;}
    .topic-badge i{font-size:.8rem}

    .post-menu{position:relative}
    .menu-trigger{display:flex; align-items:center; justify-content:center;width:36px; height:36px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;color:#64748b;padding:0; cursor:pointer;transition:.2s;box-shadow:0 1px 2px rgba(0,0,0,.04)}
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

    .post-content{margin-bottom:1rem}
    .content-text{color:#4a5568;line-height:1.6;margin:0}

    .read-more{color:#2563eb;font-weight:600;font-size:.9rem;text-decoration:none;display:inline-block;margin-top:.3rem}
    .read-more:hover{text-decoration:underline}

    /* Image du post */
    .post-image{margin-top:.6rem}
    .post-image img{
        width:100%;
        max-height:420px;
        object-fit:cover;
        border-radius:12px;
        border:1px solid var(--border);
        box-shadow:var(--shadow);
        display:block;
    }

    .post-stats{display:flex;align-items:center;gap:1.5rem;margin-bottom:.5rem;padding:.75rem 0;border-top:1px solid #f1f5f9;border-bottom:1px solid #f1f5f9}
    .stat-item{display:flex;align-items:center;gap:.5rem;color:#64748b;font-size:.875rem;font-weight:500}
    .stat-item:hover{color:#3b82f6}
    .stat-item .fa-heart{color:#ef4444}
    .stat-item .fa-comment{color:#06b6d4}

    .post-action-buttons{display:flex;justify-content:center;align-items:center;border-top:1px solid #f1f5f9;padding-top:.75rem;gap:2rem}
    .like-form{flex:0 1 auto;margin:0}
    .action-btn{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.8rem 1.5rem;border:none;background:none;color:#64748b;cursor:pointer;transition:.2s;border-radius:.375rem;text-decoration:none;font-size:.9rem;font-weight:500;min-height:40px;min-width:350px}
    .action-btn:hover{background:#f8fafc;color:#374151;transform:translateY(-1px)}
    .action-btn:disabled{opacity:.6;cursor:not-allowed;transform:none}
    .like-btn:hover .like-icon{transform:scale(1.1)}
    .like-icon{transition:.3s;color:#6b7280;font-size:1.1rem}
    .like-icon.liked{color:#ef4444!important}
    .comment-btn:hover .comment-icon{transform:scale(1.1)}
    .comment-icon{transition:.3s;font-size:1.1rem;color:#6b7280}
    .action-text{font-weight:600;white-space:nowrap}

    .empty-state{text-align:center;padding:3rem 1rem}
    .empty-icon{font-size:4rem;color:#cbd5e0;margin-bottom:1rem}

    .pagination-wrap{margin:1rem 0;display:flex;justify-content:center}
    .pagination{gap:.4rem}
    .pagination li{display:inline-block}
    .pagination a,.pagination span{padding:.5rem .75rem;border:1px solid var(--border);background:#fff;margin:0 .15rem;border-radius:10px;text-decoration:none;color:#111827}
    .pagination .active span{background:var(--brand);border-color:var(--brand);color:#fff}

    @media (max-width:768px){
        .filters-row{align-items:stretch}
        .select-wrap{min-width:unset}
        .post-header{flex-direction:column;gap:1rem}
        .post-header-right{gap:.5rem;align-self:flex-end}
        .post-menu{position:absolute;top:1rem;right:1rem}
        .post-stats{gap:1rem;justify-content:center}
        .post-action-buttons{flex-direction:row;justify-content:space-around;gap:1rem}
        .action-btn{justify-content:center;padding:.75rem 1rem;min-width:100px}
        .btn{width:100%;justify-content:center}
    }
    @media (max-width:480px){
        .post-action-buttons{flex-direction:column;gap:.75rem}
        .action-btn{padding:.75rem 1rem;font-size:.85rem;min-width:100%}
        .action-text{font-size:.85rem}
    }

    /* ===== Report Modal (inchangé) ===== */
    .report-modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:10000;align-items:center;justify-content:center;padding:20px}
    .report-modal-content{background:#fff;border-radius:12px;width:100%;max-width:500px;max-height:80vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 10px 25px rgba(0,0,0,.2)}
    .report-modal-header{padding:20px 24px;border-bottom:1px solid #e4e6ea;display:flex;justify-content:space-between;align-items:center}
    .report-modal-header h2{margin:0;font-size:1.25rem;font-weight:600;color:#1c1e21}
    .close-modal{background:none;border:none;font-size:24px;cursor:pointer;color:#65676b;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:50%}
    .close-modal:hover{background:#f0f2f5}
    .report-modal-body{padding:20px 24px;overflow-y:auto;flex:1}
    .report-modal-body h3{margin:0 0 8px 0;font-size:1.125rem;font-weight:600;color:#1c1e21}
    .report-subtitle{margin:0 0 20px 0;color:#65676b;font-size:.875rem;line-height:1.4}
    .report-reasons{display:flex;flex-direction:column;gap:20px}
    .reason-category h4{margin:0 0 12px 0;font-size:.875rem;font-weight:600;color:#65676b;text-transform:uppercase;letter-spacing:.5px}
    .reason-options{display:flex;flex-direction:column;gap:8px}
    .reason-option{display:flex;align-items:flex-start;padding:12px;border:2px solid #e4e6ea;border-radius:8px;cursor:pointer;transition:.2s;background:#fff}
    .reason-option:hover{border-color:#1877f2;background:#f7f8fa}
    .reason-option input[type="radio"]{margin:2px 12px 0 0;transform:scale(1.1)}
    .reason-text{flex:1;display:flex;flex-direction:column;gap:4px}
    .reason-text strong{font-weight:600;color:#1c1e21;font-size:.95rem}
    .reason-description{color:#65676b;font-size:.875rem;line-height:1.3}
    .reason-option input[type="radio"]:checked + .reason-text strong{color:#1877f2}
    .reason-option input[type="radio"]{accent-color:#1877f2}
    .additional-info{margin-top:20px;padding-top:20px;border-top:1px solid #e4e6ea}
    .details-label{display:block;margin-bottom:8px;font-weight:600;color:#1c1e21;font-size:.95rem}
    .report-details{width:100%;padding:12px;border:1px solid #dddfe2;border-radius:6px;font-family:inherit;font-size:.95rem;resize:vertical;transition:border-color .2s}
    .report-details:focus{outline:none;border-color:#1877f2;box-shadow:0 0 0 2px rgba(24,119,242,.2)}
    .report-modal-footer{padding:20px 24px;border-top:1px solid #e4e6ea;display:flex;gap:12px;justify-content:flex-end}
    .report-modal-footer .btn{min-width:80px}
    .report-confirmation{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:10001;align-items:center;justify-content:center;padding:20px}
    .confirmation-content{background:#fff;border-radius:12px;padding:30px;text-align:center;max-width:400px;width:100%;box-shadow:0 10px 25px rgba(0,0,0,.2)}
    .confirmation-icon{font-size:48px;color:#42a642;margin-bottom:16px}
    @media (max-width:768px){
        .report-modal{padding:10px;align-items:flex-end}
        .report-modal-content{max-height:90vh;border-radius:12px 12px 0 0}
        .report-confirmation{padding:10px}
        .confirmation-content{border-radius:12px}
    }
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
.post-title {
    font-weight: 700;
    font-size: 2rem;
    color: #1e293b; /* bleu-gris foncé */
    margin-bottom: 0.25rem;
}
</style>
@endsection

@include('user.GestionForum.partials.report-modal')
