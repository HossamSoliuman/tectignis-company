<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::published()->ordered()->get();

        return view('public.blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();
        $recentPosts = BlogPost::published()->latest('published_at')->limit(5)->get();

        return view('public.blog.show', compact('post', 'recentPosts'));
    }
}
