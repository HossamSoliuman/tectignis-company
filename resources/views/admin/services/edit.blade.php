@extends('layouts.admin')

@section('title', 'Edit Service')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.services.index') }}" class="text-sm text-fuchsia-600 hover:underline">← Back to Services</a>
    </div>

    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data"
        class="max-w-2xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.services._form')
        <div class="flex justify-end">
            <button type="submit"
                class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
                Update Service
            </button>
        </div>
    </form>
@endsection
