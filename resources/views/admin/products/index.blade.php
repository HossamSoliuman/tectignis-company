@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="cube" class="h-5 w-5 text-fuchsia-600" />
            Products
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $products->total() }}</span>
        </h2>
        <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
            <x-admin.search-form placeholder="Search products…" />
            <a href="{{ route('admin.products.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Product
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[40rem] text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Icon</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Website</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($products as $product)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 text-fuchsia-600"><i class="{{ $product->icon }}"></i></td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $product->name }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ Str::limit($product->description, 60) }}</td>
                        <td class="px-4 py-3 text-slate-500">
                            @if ($product->url)
                                <a href="{{ $product->url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                    {{ parse_url($product->url, PHP_URL_HOST) ?? $product->url }} <x-admin.icon name="external-link" class="h-3.5 w-3.5" />
                                </a>
                            @else
                                <span class="text-slate-300">None</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $product->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$product->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.products.edit', $product)" />
                                <x-admin.delete-button :action="route('admin.products.destroy', $product)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin.pagination :paginator="$products" />
@endsection
