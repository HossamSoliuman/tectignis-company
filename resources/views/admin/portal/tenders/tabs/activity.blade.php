@extends('admin.portal.tenders.workspace')

@section('tab')
    <x-portal.panel title="Activity Log" icon="clock" :count="$logs->total()">
        <ol class="divide-y divide-slate-100">
            @forelse ($logs as $log)
                <li class="px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-sm font-medium text-slate-800">
                            {{ $log->user?->name ?? 'System' }}
                            <span class="font-normal text-slate-500">{{ $log->description }}</span>
                        </p>
                        <span class="text-xs text-slate-400">{{ $log->created_at?->format('d M Y, g:i A') }}</span>
                    </div>
                    <p class="mt-0.5 text-xs text-slate-400">{{ $log->subjectLabel() }} · {{ Str::headline($log->event) }}</p>

                    @php
                        // The observer stores before/after maps, so an update shows the
                        // fields that moved and a create shows what it started as.
                        $before = $log->changes['before'] ?? [];
                        $after = $log->changes['after'] ?? [];
                        $fields = array_slice(array_keys($after ?: $before), 0, 8);
                    @endphp
                    @if ($fields !== [] && $log->event === 'updated')
                        <dl class="mt-2 space-y-0.5 rounded-lg bg-slate-50 px-3 py-2 text-xs">
                            @foreach ($fields as $field)
                                <div class="flex flex-wrap gap-x-2">
                                    <dt class="font-medium text-slate-500">{{ Str::headline($field) }}</dt>
                                    <dd class="text-slate-600">
                                        <span class="text-slate-400">{{ Str::limit((string) ($before[$field] ?? '—'), 60) }}</span>
                                        <span class="text-slate-300">→</span>
                                        <span>{{ Str::limit((string) ($after[$field] ?? '—'), 60) }}</span>
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </li>
            @empty
                <li><x-portal.empty-state message="Nothing has happened on this tender yet." icon="clock" /></li>
            @endforelse
        </ol>
    </x-portal.panel>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
