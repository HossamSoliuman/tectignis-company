@extends('layouts.admin')
@section('title', 'Create FAQ Category')
@section('content')
    <div class="mb-4"><a href="{{ route('admin.faq-categories.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back</a></div>
    <form action="{{ route('admin.faq-categories.store') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @include('admin.faq-categories._form')
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Create</button>
        </div>
    </form>
@endsection
