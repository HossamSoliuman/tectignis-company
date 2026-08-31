@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-portal.kpi-card label="Eligibility" tone="violet" icon="scale"
            :value="$summary['eligibility']['answered'].' / '.$summary['eligibility']['total']"
            :hint="$summary['eligibility']['not_met'] > 0 ? $summary['eligibility']['not_met'].' requirement(s) not met' : 'answered'" />
        <x-portal.kpi-card label="Mandatory Documents" :tone="$summary['documents']['mandatory_missing'] > 0 ? 'rose' : 'emerald'" icon="document-text"
            :value="($summary['documents']['mandatory'] - $summary['documents']['mandatory_missing']).' / '.$summary['documents']['mandatory']"
            :hint="$summary['documents']['expired'] > 0 ? $summary['documents']['expired'].' expired on file' : 'on file'" />
        <x-portal.kpi-card label="Open Tasks" :tone="$summary['tasks']['overdue'] > 0 ? 'amber' : 'sky'" icon="clipboard-list"
            :value="$summary['tasks']['open']"
            :hint="$summary['tasks']['overdue'].' overdue'" />
        <x-portal.kpi-card label="OEM Pending" :tone="$summary['oem']['overdue'] > 0 ? 'rose' : 'slate'" icon="share"
            :value="$summary['oem']['pending']"
            :hint="$summary['oem']['overdue'].' past required-by'" />
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="space-y-5 lg:col-span-2">
            <x-portal.panel title="Mandatory Documents Outstanding" icon="document-text" :count="$missingDocuments->count()"
                :action="route('admin.portal.tenders.documents.index', $tender)">
                <ul class="divide-y divide-slate-100">
                    @forelse ($missingDocuments as $document)
                        <li class="flex flex-wrap items-center justify-between gap-3 px-4 py-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $document->name }}</p>
                                <p class="text-xs text-slate-400">{{ $document->category->label() }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-portal.badge :color="$document->status->badgeColor()" :label="$document->status->label()" />
                                <x-portal.due-date :date="$document->required_by" />
                            </div>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="Every mandatory document is accounted for." icon="check-circle" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>

            <x-portal.panel title="Next Steps" icon="clipboard-list" :count="$upcomingTasks->count()"
                :action="route('admin.portal.tenders.tasks.index', $tender)">
                <ul class="divide-y divide-slate-100">
                    @forelse ($upcomingTasks as $task)
                        <li class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 {{ $task->isOverdue() ? 'bg-rose-50/40' : '' }}">
                            <div class="min-w-0">
                                <a href="{{ route('admin.portal.tasks.show', $task) }}" class="truncate text-sm font-medium text-slate-800 hover:text-fuchsia-700">{{ $task->title }}</a>
                                <p class="text-xs text-slate-400">{{ $task->assignee?->name ?? 'Unassigned' }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-portal.badge :color="$task->status->badgeColor()" :label="$task->status->label()" />
                                <x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" />
                            </div>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="No open tasks on this tender." icon="clipboard-list" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>

            @if ($tender->notes)
                <x-portal.panel title="Notes" icon="pencil">
                    <p class="whitespace-pre-line px-4 py-3 text-sm text-slate-600">{{ $tender->notes }}</p>
                </x-portal.panel>
            @endif
        </div>

        <div class="space-y-5">
            <x-portal.panel title="Readiness" icon="chart-bar">
                <dl class="space-y-3 px-4 py-4 text-sm">
                    @foreach ([
                        'Eligibility' => [$summary['eligibility']['answered'], $summary['eligibility']['total']],
                        'Documents' => [$summary['documents']['settled'], $summary['documents']['total']],
                        'Tasks' => [$summary['tasks']['completed'], $summary['tasks']['total']],
                        'OEM' => [$summary['oem']['received'], $summary['oem']['total']],
                        'Submission checks' => [$summary['submission']['checked'], $summary['submission']['total']],
                    ] as $label => [$done, $total])
                        <div>
                            <div class="mb-1 flex justify-between text-xs">
                                <dt class="text-slate-500">{{ $label }}</dt>
                                <dd class="text-slate-400">{{ $done }} / {{ $total }}</dd>
                            </div>
                            <x-portal.progress-bar :value="$total > 0 ? round($done / $total * 100) : 0" :show-label="false" />
                        </div>
                    @endforeach
                </dl>
            </x-portal.panel>

            <x-portal.panel title="OEM Chases Open" icon="share" :count="$dueOem->count()"
                :action="route('admin.portal.tenders.oem.index', $tender)">
                <ul class="divide-y divide-slate-100">
                    @forelse ($dueOem as $followup)
                        <li class="px-4 py-3">
                            <div class="flex items-center justify-between gap-2">
                                <p class="truncate text-sm font-medium text-slate-800">{{ $followup->oem_name }}</p>
                                <x-portal.badge :color="$followup->status->badgeColor()" :label="$followup->status->label()" />
                            </div>
                            <p class="mt-0.5 text-xs {{ $followup->isChaseDue() ? 'font-medium text-rose-600' : 'text-slate-400' }}">
                                {{ $followup->requirement_type->label() }}
                                @if ($followup->next_followup_at)
                                    · chase {{ $followup->next_followup_at->diffForHumans() }}
                                @endif
                            </p>
                        </li>
                    @empty
                        <li><x-portal.empty-state message="Nothing outstanding from any OEM." icon="share" /></li>
                    @endforelse
                </ul>
            </x-portal.panel>

            <x-portal.panel title="Commercials" icon="scale">
                <dl class="space-y-2 px-4 py-3 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Estimated value</dt>
                        <dd class="font-medium text-slate-700">{{ $tender->estimated_value ? '₹'.number_format((float) $tender->estimated_value) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">EMD</dt>
                        <dd class="text-slate-700">{{ $tender->emd_required ? '₹'.number_format((float) $tender->emd_amount) : 'Not required' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Tender fee</dt>
                        <dd class="text-slate-700">{{ $tender->fee_required ? '₹'.number_format((float) $tender->fee_amount) : 'Not required' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Pre-bid meeting</dt>
                        <dd class="text-slate-700">{{ $tender->pre_bid_at?->format('d M Y, g:i A') ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-400">Clarifications open</dt>
                        <dd class="text-slate-700">{{ $summary['clarifications']['pending'] }}</dd>
                    </div>
                </dl>
            </x-portal.panel>
        </div>
    </div>
@endsection
