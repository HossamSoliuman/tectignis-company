@extends('layouts.admin')

@section('title', 'Blog Posts')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="document-text" class="h-5 w-5 text-fuchsia-600" />
            Blog Posts
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $posts->count() }}</span>
        </h2>
        <a href="{{ route('admin.blog.create') }}"
            class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
            <x-admin.icon name="plus" class="h-4 w-4" /> New Post
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Published</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($posts as $post)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3">
                            @if ($post->image)
                                <img src="{{ asset('uploads/'.$post->image) }}" alt="{{ $post->title }}"
                                    class="h-9 w-14 rounded-lg object-cover ring-1 ring-slate-100">
                            @else
                                <span class="flex h-9 w-14 items-center justify-center rounded-lg bg-slate-100 text-slate-300">
                                    <x-admin.icon name="photograph" class="h-5 w-5" />
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $post->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $post->slug }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$post->is_published" activeLabel="Published" inactiveLabel="Draft" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.blog.edit', $post)" />
                                <x-admin.delete-button :action="route('admin.blog.destroy', $post)" confirm="Delete this post?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No blog posts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
