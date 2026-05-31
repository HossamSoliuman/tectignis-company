@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $label => $value)
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500">{{ $label }}</div>
                <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $value }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Welcome to the Tectignis admin</h2>
        <p class="mt-2 text-sm text-slate-600">
            The foundation is ready. Content modules (services, solutions, industries, blog, insights, downloads,
            FAQs, case studies, testimonials, leads, menus, settings and per-page SEO) will appear in the sidebar as
            each is built in the following phases.
        </p>
    </div>
@endsection
