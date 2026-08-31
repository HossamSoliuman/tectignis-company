@extends('layouts.admin')

@section('title', 'New Tender')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.tenders.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to tenders
        </a>
    </div>

    <form action="{{ route('admin.portal.tenders.store') }}" method="POST" class="max-w-3xl space-y-6">
        @csrf
        @include('admin.portal.tenders._form')

        <div class="rounded-xl border border-fuchsia-100 bg-fuchsia-50/60 p-5">
            <label class="flex items-start gap-3 text-sm font-medium text-slate-800">
                <input type="hidden" name="apply_task_template" value="0">
                <input type="checkbox" name="apply_task_template" value="1" checked class="mt-0.5 rounded border-slate-300">
                <span>
                    Create the standard tender tasks
                    <span class="mt-0.5 block text-xs font-normal text-slate-500">
                        The twelve steps from download to submission, back-scheduled from the deadline. The eligibility,
                        document and submission checklists are always created.
                    </span>
                </span>
            </label>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Create Tender</button>
        </div>
    </form>
@endsection
