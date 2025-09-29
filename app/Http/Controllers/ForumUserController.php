<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;

class ForumUserController extends Controller
{
    //////////////////////////////////////////////// CRUD POSTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    // Display a listing of the posts.
    public function index()
    {
        $posts = Post::with(['user', 'topic'])
                    ->latest()
                    ->get();
        
        return view('user.GestionForum.posts.index', compact('posts'));
    }

    // Show the form for creating a new post.
    public function create()
    {
        $topics = Topic::all();
        return view('user.GestionForum.posts.create', compact('topics'));
    }

    // Store a newly created post in storage.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content_P' => 'required|string|min:10|max:1000',
            'topic_id' => 'required|exists:topics,id',
        ]);

        // TEMPORAIRE: Utiliser un user_id par défaut (1 par exemple)
        $validated['created_by'] = 1; // User avec ID 1

        Post::create($validated);

        return redirect()->route('user.posts.index')
            ->with('success', 'Post créé avec succès!');
    }

    // Display the specified post.
    public function show(Post $post)
    {
        $post->load(['user', 'topic', 'comments.user']);
        return view('user.GestionForum.posts.show', compact('post'));
    }

    // Show the form for editing the specified post.
    public function edit(Post $post)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($post->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $topics = Topic::all();
        return view('user.GestionForum.posts.edit', compact('post', 'topics'));
    }

    // Update the specified post in storage.
    public function update(Request $request, Post $post)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($post->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $validated = $request->validate([
            'content_P' => 'required|string|min:10|max:1000',
            'topic_id' => 'required|exists:topics,id',
        ]);

        $post->update($validated);

        return redirect()->route('user.posts.index')
            ->with('success', 'Post modifié avec succès!');
    }

    // Remove the specified post from storage.
    public function destroy(Post $post)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($post->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $post->delete();

        return redirect()->route('user.posts.index')
            ->with('success', 'Post supprimé avec succès!');
    }

    //////////////////////////////////////////////// CRUD COMMENTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

        public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content_C' => 'required|string|min:3|max:500',
        ]);

        // TEMPORAIRE: Utiliser user_id 1
        $validated['created_by'] = 1;
        $validated['postId'] = $post->id;

        Comment::create($validated);

        return redirect()->route('user.posts.show', $post)
            ->with('success', 'Commentaire ajouté avec succès!');
    }

        public function editComment(Comment $comment)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($comment->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        return view('user.GestionForum.comments.edit', compact('comment'));
    }

        public function updateComment(Request $request, Comment $comment)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($comment->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $validated = $request->validate([
            'content_C' => 'required|string|min:3|max:500',
        ]);

        $comment->update($validated);

        return redirect()->route('user.posts.show', $comment->post)
            ->with('success', 'Commentaire modifié avec succès!');
    }

        public function destroyComment(Comment $comment)
    {
        // TEMPORAIRE: Ignorer la vérification d'authentification
        // if ($comment->created_by !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('user.posts.show', $post)
            ->with('success', 'Commentaire supprimé avec succès!');
    }


//////////////////////////////////////////////// LIKES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    // Toggle like/unlike for a post
    public function toggle(Request $request, Post $post)
    {
        // TEMPORAIRE: Utiliser user_id 1
        $userId = 1;

        // Vérifier si l'utilisateur a déjà liké ce post
        $existingLike = Like::where('liked_by', $userId)
                            ->where('postId', $post->id)
                            ->first();

        if ($existingLike) {
            // Si like existe, le supprimer (unlike)
            $existingLike->delete();
            $liked = false;
            $message = 'Like retiré';
        } else {
            // Sinon, créer un nouveau like
            Like::create([
                'liked_by' => $userId,
                'postId' => $post->id,
            ]);
            $liked = true;
            $message = 'Post liké';
        }

        // Recharger les relations pour avoir le compte à jour
        $post->load('likes');

        if ($request->ajax()) {
            // Retourner une réponse JSON pour les requêtes AJAX
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $post->likes->count(),
                'message' => $message
            ]);
        }

        return redirect()->route('user.posts.show', $post)
            ->with('success', $message);
    }

    // Check if current user liked the post
    public function checkLike(Post $post)
    {
        // TEMPORAIRE: Utiliser user_id 1
        $userId = 1;

        $liked = Like::where('liked_by', $userId)
                    ->where('postId', $post->id)
                    ->exists();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes->count()
        ]);
    }

}