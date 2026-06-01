@extends('layouts.admin')

@section('title', 'Edit Blog Post')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-fuchsia-600 hover:underline">← Back to Blog Posts</a>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data"
        class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.blog._form')
        <div class="flex justify-end">
            <button type="submit"
                class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
                Update Post
            </button>
        </div>
    </form>
@endsection
