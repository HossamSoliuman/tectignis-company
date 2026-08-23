@extends('layouts.admin')
@section('title', 'Daily Work Update')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.daily-work.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to daily work
        </a>
    </div>
    <form action="{{ route('admin.portal.daily-work.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-5">
        @csrf
        @include('admin.portal.daily-work._form')
        <button type="submit"
            class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700 sm:w-auto sm:py-2">
            Submit Update
        </button>
    </form>
@endsection
