@extends('layouts.admin')

@section('title', 'Create Service')

@section('content')
    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data"
        class="max-w-4xl space-y-5">
        @csrf

        {{-- Sticky top bar --}}
        <div class="sticky top-0 z-20 -mx-1 flex items-center justify-between rounded-xl border border-slate-200 bg-white/90 px-4 py-2.5 shadow-sm backdrop-blur">
            <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
                <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Services
            </a>
            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                Create Service
            </button>
        </div>

        @include('admin.services._form')
    </form>
@endsection
