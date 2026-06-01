@extends('layouts.admin')
@section('title', 'Create Testimonial')
@section('content')
    <div class="mb-4"><a href="{{ route('admin.testimonials.index') }}" class="text-sm text-fuchsia-600 hover:underline">← Back</a></div>
    <form action="{{ route('admin.testimonials.store') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @include('admin.testimonials._form')
        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">Create</button>
        </div>
    </form>
@endsection
