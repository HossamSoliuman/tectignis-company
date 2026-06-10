<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $category = (string) $request->query('category', '');

        $posts = BlogPost::published()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('public.blog.index', [
            'posts' => $posts,
            'search' => $search,
            'activeCategory' => $category,
            'categories' => $this->categoriesWithCounts(),
            'popularPosts' => $this->popularPosts(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('public.blog.show', [
            'post' => $post,
            'categories' => $this->categoriesWithCounts(),
            'popularPosts' => $this->popularPosts($post->id),
        ]);
    }

    /** @return Collection<string, int> */
    private function categoriesWithCounts(): Collection
    {
        return BlogPost::published()
            ->whereNotNull('category')
            ->selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->orderBy('category')
            ->pluck('total', 'category');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, BlogPost> */
    private function popularPosts(?int $exceptId = null): \Illuminate\Database\Eloquent\Collection
    {
        return BlogPost::published()
            ->when($exceptId, fn ($query) => $query->whereKeyNot($exceptId))
            ->latest('published_at')
            ->limit(4)
            ->get();
    }
}
