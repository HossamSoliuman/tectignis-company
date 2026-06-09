@extends('layouts.admin')

@section('title', 'Create Feature')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.why-choose-features.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Features</a>
    </div>

    <form action="{{ route('admin.why-choose-features.store') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @include('admin.why-choose-features._form')
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                Create Feature
            </button>
        </div>
    </form>
@endsection
