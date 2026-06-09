@extends('layouts.admin')

@section('title', 'Edit Advantage')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.global-advantages.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Advantages</a>
    </div>

    <form action="{{ route('admin.global-advantages.update', $advantage) }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.global-advantages._form')
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                Update Advantage
            </button>
        </div>
    </form>
@endsection
