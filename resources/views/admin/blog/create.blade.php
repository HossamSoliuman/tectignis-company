@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Blog Posts</a>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_18rem] gap-6 items-start">
            <div>
                @include('admin.blog._form')
            </div>
            <div class="space-y-4 xl:sticky xl:top-20 self-start">
                <div class="rounded-xl border border-slate-200 bg-white p-5 space-y-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Details</h3>
                    <x-admin.image-field name="image" label="Featured Image" :current="null" />
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                        <p class="mt-1 text-xs text-slate-400">Lower numbers appear first.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" id="is_published" name="is_published" value="1"
                            class="rounded border-slate-300">
                        <label for="is_published" class="text-sm font-medium text-slate-700">Published</label>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                        Create Post
                    </button>
                    <a href="{{ route('admin.blog.index') }}"
                        class="w-full inline-flex justify-center items-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection
