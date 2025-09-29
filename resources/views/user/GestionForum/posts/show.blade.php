@extends('layouts.user-layout')

@section('title', $post->title)
@section('content')
<div class="forum-container">
    <div class="forum-header">
        <div class="header-content">
            <h1>Post Details</h1>
            <p>View the complete information of the post</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('user.posts.edit', $post) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i>
                Edit
            </a>
            <a href="{{ route('user.posts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Back
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
        <!-- Post Principal -->
        <div class="main-post">
            <div class="post-header">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <h3>{{ $post->user->name ?? 'Utilisateur' }}</h3>
                        <span class="post-time">
                            {{ $post->P_created_at->diffForHumans() }}
                            • in <span class="topic-badge">{{ $post->topic->title }}</span>
                        </span>
                    </div>
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
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <span>0 views</span>
                </div>
            </div>
        </div>

        <!-- Section Commentaires -->
        <div class="comments-section">
            <h3>Comments ({{ $post->comments->count() }})</h3>
            
            @if($post->comments->count() > 0)
                <div class="comments-list">
                    @foreach($post->comments as $comment)
                        <div class="comment-item">
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
                                <div class="comment-actions">
                                    @if($comment->created_by == auth()->user()->id) 
                                        <a href="{{ route('user.comments.edit', $comment) }}" class="btn btn-xs btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('user.comments.destroy', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger" 
                                                    onclick="return confirm('Supprimer ce commentaire?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <div class="comment-content">
                                <p>{{ $comment->content_C }}</p>
                            </div>
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

            <!-- Comment Form -->
            <div class="comment-form">
                <h4>Add a Comment</h4>
                <form action="{{ route('user.comments.store', $post) }}" method="POST">
                    @csrf
                    <textarea name="content_C" rows="3" placeholder="Write your comment..." 
                            class="form-control" required></textarea>
                    @error('content_C')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Comment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Actions du post -->
    <div class="post-actions-section">
        <div class="action-buttons">
            <form action="{{ route('user.likes.toggle', $post) }}" method="POST" class="like-form d-inline">
                @csrf
                <button type="submit" class="btn btn-outline like-btn" 
                        data-post-id="{{ $post->id }}"
                        data-liked="{{ $post->likes->contains('liked_by', auth()->user()->id) ? 'true' : 'false' }}">
                    <i class="fas fa-heart like-icon"></i>
                    <span class="like-text">
                        {{ $post->likes->contains('liked_by', auth()->user()->id) ? 'Unlike' : 'Like' }}
                    </span>
                    <span class="likes-count">({{ $post->likes->count() }})</span>
                </button>
            </form>
            
            <button class="btn btn-outline" onclick="document.getElementById('comment-form').scrollIntoView()">
                <i class="fas fa-comment"></i>
                Comment
            </button>
            
            <button class="btn btn-outline">
                <i class="fas fa-share"></i>
                Share
            </button>
        </div>

        <!-- Zone de danger pour le propriétaire du post -->
        @if($post->created_by == 1)
            <div class="danger-zone">
                <h4>Danger Zone</h4>
                <p>Deleting this post is irreversible.</p>
                <form action="{{ route('user.posts.destroy', $post) }}" method="POST" 
                    onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>
                        Delete this post
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
 // ========== SYSTÈME DE LIKES ==========
    const likeForms = document.querySelectorAll('.like-form');
    
    likeForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = form.querySelector('.like-btn');
            const likeIcon = form.querySelector('.like-icon');
            const likeText = form.querySelector('.like-text');
            const likesCount = form.querySelector('.likes-count');
            const currentLiked = button.dataset.liked === 'true';
            
            // Désactiver le bouton pendant la requête
            button.disabled = true;
            
            // Envoyer la requête AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le data-attribute
                    button.dataset.liked = data.liked.toString();
                    
                    // Mettre à jour l'icône
                    if (data.liked) {
                        likeIcon.style.color = '#ef4444';
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas');
                    } else {
                        likeIcon.style.color = '#6b7280';
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                    }
                    
                    // Mettre à jour le texte
                    likeText.textContent = data.liked ? 'Unlike' : 'Like';
                    
                    // Mettre à jour le compteur
                    likesCount.textContent = `(${data.likes_count})`;
                    
                    // Mettre à jour aussi le compteur dans les stats
                    const statsLikes = document.querySelector('.post-stats .stat-item:nth-child(1) span');
                    if (statsLikes) {
                        statsLikes.textContent = `${data.likes_count} likes`;
                    }
                    
                    // Afficher un message toast
                    showToast(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Une erreur est survenue', 'error');
            })
            .finally(() => {
                // Réactiver le bouton
                button.disabled = false;
            });
        });
    });
    
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? '#10b981' : '#ef4444';
        
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            font-family: inherit;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        `;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 2000);
    }
    
    // Ajouter les animations CSS
    if (!document.querySelector('#like-styles')) {
        const style = document.createElement('style');
        style.id = 'like-styles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .like-btn {
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                border: 1px solid #d1d5db;
                background: transparent;
                color: #4b5563;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                cursor: pointer;
                text-decoration: none;
            }
            
            .like-btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }
            
            .like-btn:hover:not(:disabled) {
                background: #f3f4f6;
                border-color: #9ca3af;
            }
            
            .like-btn:hover:not(:disabled) .like-icon {
                transform: scale(1.1);
            }
            
            .like-icon {
                transition: all 0.3s ease;
            }
            
            .action-buttons {
                display: flex;
                gap: 1rem;
                margin-bottom: 1.5rem;
                flex-wrap: wrap;
            }
            
            .btn-outline {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                border: 1px solid #d1d5db;
                background: transparent;
                color: #4b5563;
                padding: 0.5rem 1rem;
                border-radius: 0.375rem;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.3s ease;
                font-family: inherit;
                font-size: 14px;
            }
            
            .btn-outline:hover {
                background: #f3f4f6;
                border-color: #9ca3af;
            }
        `;
        document.head.appendChild(style);
    }
    
</script>
@endsection

@section('styles')
<style>
    /* === STYLES GLOBAUX USER === */
    .forum-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .forum-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .forum-header h1 {
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .forum-header p {
        color: #718096;
        margin-bottom: 1.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 0.375rem;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-xs {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        min-height: auto;
    }

    .alert {
        padding: 1rem;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    /* === STYLES SPÉCIFIQUES POST DÉTAIL === */
    .header-content {
        text-align: center;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-top: 1rem;
    }

    .post-detail-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .main-post {
        background: white;
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .post-header {
        margin-bottom: 1.5rem;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }


    .user-avatar-sm {
        width: 35px;
        height: 35px;
        background: #6b7280;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
    }

    .user-details h3 {
        margin: 0;
        color: #2d3748;
        font-size: 1.25rem;
    }

    .user-details h4 {
        margin: 0;
        color: #2d3748;
        font-size: 1rem;
    }

    .post-time, .comment-time {
        color: #718096;
        font-size: 0.875rem;
    }

    .topic-badge {
        background: #e2e8f0;
        color: #4a5568;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .post-content {
        margin-bottom: 1.5rem;
    }

    .post-content p {
        color: #4a5568;
        line-height: 1.7;
        font-size: 1.1rem;
        margin: 0;
    }

    .post-stats {
        display: flex;
        gap: 2rem;
        border-top: 1px solid #e2e8f0;
        padding-top: 1rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.875rem;
    }

    .comments-section {
        background: white;
        border-radius: 0.75rem;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .comments-section h3 {
        color: #2d3748;
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
    }

    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .comment-item {
        padding: 1.5rem;
        border: 1px solid #f1f5f9;
        border-radius: 0.5rem;
        background: #f8fafc;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .comment-content p {
        color: #4a5568;
        line-height: 1.6;
        margin: 0;
    }

    .comment-actions {
        display: flex;
        gap: 0.25rem;
    }

    .empty-comments {
        text-align: center;
        padding: 3rem 2rem;
        color: #718096;
    }

    .empty-comments i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .comment-form {
        border-top: 1px solid #e2e8f0;
        padding-top: 1.5rem;
    }

    .comment-form h4 {
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .post-actions-section {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-outline {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #d1d5db;
        background: transparent;
        color: #4b5563;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
        font-family: inherit;
        font-size: 14px;
    }

    .btn-outline:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .like-btn {
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #d1d5db;
        background: transparent;
        color: #4b5563;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        cursor: pointer;
        text-decoration: none;
    }

    .like-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .like-btn:hover:not(:disabled) {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .like-btn:hover:not(:disabled) .like-icon {
        transform: scale(1.1);
    }

    .like-icon {
        transition: all 0.3s ease;
    }

    .danger-zone {
        border-top: 1px solid #fecaca;
        padding-top: 1.5rem;
    }

    .danger-zone h4 {
        color: #dc2626;
        margin-bottom: 0.5rem;
    }

    .danger-zone p {
        color: #7f1d1d;
        margin-bottom: 1rem;
        font-size: 0.875rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 1rem;
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .forum-container {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }
        
        .header-actions {
            flex-direction: column;
            align-items: center;
        }
        
        .main-post,
        .comments-section,
        .post-actions-section {
            padding: 1.5rem;
        }
        
        .post-stats {
            flex-direction: column;
            gap: 1rem;
        }
        
        .action-buttons {
            justify-content: center;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
        
        .comment-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .comment-actions {
            align-self: flex-end;
        }
    }
</style>

@endsection