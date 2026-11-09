@extends('layouts.admin')

@section('title', 'Edit Case Study')

@section('content')
    <form action="{{ route('admin.case-studies.update', $caseStudy) }}" method="POST" enctype="multipart/form-data" class="max-w-6xl space-y-5">
        @csrf
        @method('PUT')

        <div class="sticky top-20 z-10 -mx-1 flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-white/90 px-4 py-2.5 shadow-sm backdrop-blur">
            <a href="{{ route('admin.case-studies.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
                <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Case Studies
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.case-studies.show', $caseStudy) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 hover:text-fuchsia-600">
                    <x-admin.icon name="eye" class="h-4 w-4" /> Preview
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-1.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    Update Case Study
                </button>
            </div>
        </div>

        @include('admin.case-studies._form')
    </form>
@endsection
