@extends('layouts.admin')

@section('title', 'Brands')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Brands ({{ $brands->count() }})</h2>
        <a href="{{ route('admin.brands.create') }}"
            class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">+ New</a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Logo</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">URL</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($brands as $brand)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            @if ($brand->logo)
                                <img src="{{ asset('assets/images/brand/'.$brand->logo) }}" alt="{{ $brand->name }}"
                                    class="h-8 w-auto object-contain">
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $brand->name }}</td>
                        <td class="px-4 py-3 text-slate-500 truncate max-w-xs">{{ $brand->url ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $brand->sort_order }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs {{ $brand->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $brand->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="text-fuchsia-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST"
                                    onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
