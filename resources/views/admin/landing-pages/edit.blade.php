@extends('layouts.admin')

@section('title', 'Edit Landing Page')

@section('content')
    <form action="{{ route('admin.landing-pages.update', $landingPage) }}" method="POST" class="max-w-4xl space-y-5">
        @csrf
        @method('PUT')

        <div class="sticky top-20 z-10 -mx-1 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white/90 px-4 py-2.5 shadow-sm backdrop-blur">
            <a href="{{ route('admin.landing-pages.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
                <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Landing Pages
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('landing-pages.show', $landingPage->slug) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-fuchsia-600">
                    <x-admin.icon name="eye" class="h-4 w-4" /> View page
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Update Landing Page
                </button>
            </div>
        </div>

        @include('admin.landing-pages._form')
    </form>
@endsection
