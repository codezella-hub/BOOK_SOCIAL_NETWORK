<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Report;
use App\Models\User; 
use App\Notifications\PostReported;
use Illuminate\Support\Facades\Notification; 
use App\Services\ContentModerator;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Storage;

class ForumUserController extends Controller
{
    //////////////////////////////////////////////// CRUD POSTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    
public function index(Request $request)
{
    $topics = Topic::orderBy('title')->get();

    $query = Post::with(['user', 'topic']);

    if ($request->filled('topic')) {
        $query->where('topic_id', $request->topic);
    }

    $sort = $request->get('sort', 'newest'); 
    $direction = $sort === 'oldest' ? 'asc' : 'desc';
    $query->orderBy('P_created_at', $direction);

    // paginate & keep query string
    $posts = $query->paginate(10)->withQueryString();

    return view('user.GestionForum.posts.index', compact('posts', 'topics'));
}


    public function create()
    {
        $topics = Topic::all();
        return view('user.GestionForum.posts.create', compact('topics'));
    }

    // Store a newly created post in storage.
public function store(Request $request, ContentModerator $moderator)
{
    $validated = $request->validate([
        'content_P' => 'required|string|min:10|max:1000',
        'topic_id'  => 'required|exists:topics,id',
        'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048', // 2MB
    ]);

    $user   = Auth::user();
    $result = $moderator->moderate($validated['content_P']);
    $data   = [
        'content_P' => $result['clean'],
        'topic_id'  => $validated['topic_id'],
        'created_by'=> $user->id,
    ];

    if ($request->hasFile('image')) {
        $data['image_path'] = $request->file('image')->store('posts','public');
    }

    Post::create($data);

    return redirect()->route('user.posts.index')
        ->with('success', $result['toxic']
            ? 'Your post had strong language. We masked some words and published it.'
            : 'Post créé avec succès!');
}

    // Display the specified post.
    public function show(Post $post)
    {
        $post->load([
            'user',
            'topic',
            'comments' => function ($q) {
                $q->topLevel()
                ->with(['user'])
                ->withCount('likes')
                ->with(['replies' => function ($qr) {
                        $qr->with(['user'])->withCount('likes');
                    }])
                ->orderBy('C_created_at');
            },
        ]);

        return view('user.GestionForum.posts.show', compact('post'));
    }

    // Show the form for editing the specified post.
    public function edit(Post $post)
    {
        $topics = Topic::all();
        return view('user.GestionForum.posts.edit', compact('post', 'topics'));
    }

    // Update the specified post in storage.
public function update(Request $request, Post $post, ContentModerator $moderator)
{
    $validated = $request->validate([
        'content_P' => 'required|string|min:10|max:1000',
        'topic_id'  => 'required|exists:topics,id',
        'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        'remove_image' => 'nullable|boolean',
    ]);

    $result = $moderator->moderate($validated['content_P']);
    $data   = [
        'content_P' => $result['clean'],
        'topic_id'  => $validated['topic_id'],
    ];

    // suppression si demandé
    if ($request->boolean('remove_image') && $post->image_path) {
        Storage::disk('public')->delete($post->image_path);
        $data['image_path'] = null;
    }

    // remplacement si nouveau fichier
    if ($request->hasFile('image')) {
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        $data['image_path'] = $request->file('image')->store('posts','public');
    }

    $post->update($data);

    return redirect()->route('user.posts.index')
        ->with('success', $result['toxic']
            ? 'Your post had strong language. We masked some words and saved it.'
            : 'Post modifié avec succès!');
}

    // Remove the specified post from storage.
    public function destroy(Post $post)
    {

        $post->delete();

        return redirect()->route('user.posts.index')
            ->with('success', 'Post supprimé avec succès!');
    }

    //////////////////////////////////////////////// CRUD COMMENTS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

public function storeComment(Request $request, Post $post, ContentModerator $moderator)
{
    $validated = $request->validate([
        'content_C' => 'required|string|min:3|max:500',
    ]);

    $result = $moderator->moderate($validated['content_C']);
    $validated['content_C'] = $result['clean'];

    $user = Auth::user();
    $validated['created_by'] = $user->id;
    $validated['postId']     = $post->id;

    Comment::create($validated);

    return redirect()->route('user.posts.show', $post)
        ->with('success', $result['toxic']
            ? 'Your comment had strong language. We masked some words.'
            : 'Commentaire ajouté avec succès!');
}

        public function editComment(Comment $comment)
    {

        return view('user.GestionForum.comments.edit', compact('comment'));
    }

public function updateComment(Request $request, Comment $comment, ContentModerator $moderator)
{
    $validated = $request->validate([
        'content_C' => 'required|string|min:3|max:500',
    ]);

    $result = $moderator->moderate($validated['content_C']);
    $validated['content_C'] = $result['clean'];

    $comment->update($validated);

    return redirect()->route('user.posts.show', $comment->post)
        ->with('success', $result['toxic']
            ? 'Strong language was masked in your comment.'
            : 'Commentaire modifié avec succès!');
}

        public function destroyComment(Comment $comment)
    {

        $post = $comment->post;
        $comment->delete();

        return redirect()->route('user.posts.show', $post)
            ->with('success', 'Commentaire supprimé avec succès!');
    }


//////////////////////////////////////////////// LIKES \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    public function toggle(Request $request, Post $post)
    {

        $user = Auth::user();
        $userId = $user->id;

        $existingLike = Like::where('liked_by', $userId)
                            ->where('postId', $post->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
            $message = 'Like retiré';
        } else {
            Like::create([
                'liked_by' => $userId,
                'postId' => $post->id,
            ]);
            $liked = true;
            $message = 'Post liké';
        }

        $post->load('likes');

        if ($request->ajax()) {
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
        $user = Auth::user();
        $userId = $user->id;

        $liked = Like::where('liked_by', $userId)
                    ->where('postId', $post->id)
                    ->exists();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes->count()
        ]);
    }
/**
     * Store a new report
     */
    public function storeReport(Request $request, Post $post)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user already reported this post
        $existingReport = Report::where('reported_by', $user->id)
                                  ->where('postId', $post->id)
                                  ->first();

        if ($existingReport) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reported this post.'
                ], 422);
            }
            return back()->with('error', 'You have already reported this post.');
        }

        // Create the report
        $report = Report::create([
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'reported_by' => $user->id,
            'postId' => $post->id,
        ]);


        $admins = User::role('admin')->get();


        if ($admins->isNotEmpty()) {
            Notification::send($admins, new PostReported($report));
        }
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Post reported successfully!'
            ]);
        }

        return back()->with('success', 'Post reported successfully!');
    }

    /**
     * Check if user already reported a post
     */
    public function checkReport(Post $post)
    {
        $user = Auth::user();
        
        $reported = Report::where('reported_by', $user->id)
                         ->where('postId', $post->id)
                         ->exists();

        return response()->json([
            'reported' => $reported
        ]);
    }
    public function toggleCommentLike(Request $request, Comment $comment)
{
    $userId = Auth::id();
    if (!$userId) {
        abort(403, 'Authentication required');
    }

    $existing = CommentLike::where('liked_by', $userId)
                           ->where('commentId', $comment->id)
                           ->first();

    if ($existing) {
        $existing->delete();
        $liked   = false;
        $message = 'Like retiré';
    } else {
        CommentLike::create([
            'liked_by'  => $userId,
            'commentId' => $comment->id,
        ]);
        $liked   = true;
        $message = 'Commentaire liké';
    }

    $comment->loadCount('likes');

    if ($request->ajax()) {
        return response()->json([
            'success'     => true,
            'liked'       => $liked,
            'likes_count' => $comment->likes_count,
            'message'     => $message,
        ]);
    }

    // fallback non-AJAX
    return back()->with('success', $message);
}

public function checkCommentLike(Comment $comment)
{
    $userId = Auth::id();

    $liked = false;
    if ($userId) {
        $liked = CommentLike::where('liked_by', $userId)
                            ->where('commentId', $comment->id)
                            ->exists();
    }

    return response()->json([
        'liked'       => $liked,
        'likes_count' => $comment->likes()->count(),
    ]);
}
public function storeReply(Request $request, Comment $comment, ContentModerator $moderator)
{
    $validated = $request->validate([
        'content_C' => 'required|string|min:2|max:500',
    ]);

    $result = $moderator->moderate($validated['content_C']);
    $clean  = $result['clean'];

    $reply = Comment::create([
        'content_C' => $clean,
        'created_by'=> Auth::id(),
        'postId'    => $comment->postId, 
        'parentId'  => $comment->id,   
    ]);

    return redirect()
        ->route('user.posts.show', $comment->postId)
        ->with('success', $result['toxic']
            ? 'Your reply had strong language. We masked some words.'
            : 'Reply added successfully!');
}

}