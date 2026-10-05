<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LeadFilterRequest;
use App\Models\Lead;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CSV export of the currently filtered leads (spec §26.6). Requires the
 * separate export permission (spec §26.7).
 */
class LeadExportController extends Controller
{
    /**
     * @var array<string, string> header => attribute or accessor
     */
    private const COLUMNS = [
        'Lead ID' => 'reference',
        'Submitted' => 'created_at',
        'Status' => 'status',
        'Name' => 'name',
        'Company' => 'company',
        'Email' => 'email',
        'Phone' => 'phone',
        'Country' => 'country',
        'Service' => 'service',
        'Budget' => 'budget',
        'Timeline' => 'timeline',
        'Source' => 'source',
        'Assigned To' => 'assignee',
        'Message' => 'message',
        'Page URL' => 'page_url',
        'UTM Source' => 'utm_source',
        'UTM Medium' => 'utm_medium',
        'UTM Campaign' => 'utm_campaign',
        'UTM Term' => 'utm_term',
        'UTM Content' => 'utm_content',
    ];

    public function __invoke(LeadFilterRequest $request): StreamedResponse
    {
        Gate::authorize('export', Lead::class);

        $query = ($request->wantsTrashed() ? Lead::onlyTrashed() : Lead::query())
            ->with('assignee:id,name')
            ->filter($request->filters())
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');

        Log::info('Leads exported', [
            'user_id' => $request->user()->id,
            'filters' => $request->filters(),
        ]);

        $filename = 'leads-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens names and currency symbols correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_keys(self::COLUMNS), escape: '');

            foreach ($query->lazy(500) as $lead) {
                fputcsv($handle, array_map(
                    fn (string $column): string => $this->sanitize($this->value($lead, $column)),
                    self::COLUMNS,
                ), escape: '');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function value(Lead $lead, string $column): string
    {
        return match ($column) {
            'reference' => $lead->reference(),
            'created_at' => $lead->created_at?->timezone(config('app.timezone'))->format('Y-m-d H:i') ?? '',
            'status' => $lead->status->label(),
            'budget' => (string) $lead->budgetLabel(),
            'timeline' => (string) $lead->timelineLabel(),
            'source' => $lead->sourceLabel(),
            'assignee' => (string) $lead->assignee?->name,
            default => (string) $lead->{$column},
        };
    }

    /**
     * Neutralise spreadsheet formula injection: a visitor-supplied value that
     * starts with =, +, -, @, tab or carriage return is prefixed with a quote.
     */
    private function sanitize(string $value): string
    {
        return $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)
            ? "'".$value
            : $value;
    }
}
