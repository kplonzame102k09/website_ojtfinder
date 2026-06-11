<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PostLiked;
use App\Notifications\NewComment;

class NewsfeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('has.company')->only('store');
    }
    

    public function index(Request $request)
    {
        $user = Auth::user();
        $followingIds = $user->following()->pluck('following_id');

        $trainingCategories = Post::whereNotNull('training_category')
            ->where('training_category', '!=', '')
            ->distinct()
            ->orderBy('training_category', 'asc')
            ->pluck('training_category');

        $query = Post::with(['user.company', 'comments.user', 'likes'])->latest();

        if ($request->filled('category')) {
            $query->where('training_category', $request->category);
        }

        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('user', function($q) use ($location) {
                $q->where('address', 'LIKE', "%{$location}%")
                ->orWhereHas('company', function($sq) use ($location) {
                    $sq->where('address', 'LIKE', "%{$location}%");
                });
            });
        }

        $posts = $query->get();

        $suggestedUsers = User::where('id', '!=', auth()->id())
            ->whereNotIn('id', $followingIds)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('pages.newsfeed', compact('user', 'posts', 'suggestedUsers', 'trainingCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required_without:image|string|max:1000',
            'training_category' => 'nullable|string|max:50',
            'image'   => 'required_without:content|image|max:2048',
            'file'    => 'nullable|file|max:5120',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'training_category' => $request->training_category,
            'content' => $request->content,
        ];

        // UPDATED: Using underscores for InfinityFree folder compatibility
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data['image'] = $request->file('image')->store('posts_images', 'public');
        }

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('posts_files', 'public');
        }

        $post = Post::create($data);

        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Content',
            'description' => 'You published a new post.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $post = Auth::user()->posts()->findOrFail($id);

        if ($post->image) { Storage::disk('public')->delete($post->image); }
        if ($post->file) { Storage::disk('public')->delete($post->file); }

        $post->delete();

        Activity::create([
            'user_id' => Auth::id(),
            'type' => 'Content',
            'description' => 'You deleted one of your posts.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    // Liked and Comment logic remains unchanged as it doesn't involve file paths
    public function toggleLike(Request $request, Post $post) 
    {
        $me = auth()->user();
        $like = $post->likes()->where('user_id', $me->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $me->id]);

            Activity::create([
                'user_id' => $me->id,
                'type' => 'Interaction',
                'description' => 'You liked ' . $post->user->name . '\'s post.',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($post->user_id !== $me->id) {
                $post->user->notify(new PostLiked($me, $post));
            }
        }

        return redirect()->to(url()->previous() . '#post-' . $post->id);
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id, 
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Interaction',
            'description' => $request->parent_id 
                ? 'You replied to a comment on ' . $post->user->name . '\'s post.'
                : 'You commented on ' . $post->user->name . '\'s post.',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->filled('parent_id')) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment->user_id !== auth()->id()) {
                $parentComment->user->notify(new NewComment(auth()->user(), $post, $comment->content, true));
            }
        } else {
            if ($post->user_id !== auth()->id()) {
                $post->user->notify(new NewComment(auth()->user(), $post, $comment->content, false));
            }
        }

        return back()->with('success', 'Comment added!');
    }   
}