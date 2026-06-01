<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $posts = BlogPost::ordered()->get();

        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(StoreBlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        $this->syncUpload($request, $data, 'image');

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post created.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = ($data['slug'] ?? null) ?: Str::slug($data['title']);

        $this->syncUpload($request, $data, 'image', $blog->image);

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('status', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $this->deleteUpload($blog->image);
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post deleted.');
    }
}
