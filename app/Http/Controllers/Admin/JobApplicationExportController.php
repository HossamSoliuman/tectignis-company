<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobApplicationFilterRequest;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CSV export of the currently filtered applications (spec §26.8). Requires
 * the separate candidate export permission. CVs are never included.
 */
class JobApplicationExportController extends Controller
{
    /**
     * @var array<string, string> header => attribute or accessor
     */
    private const COLUMNS = [
        'Application ID' => 'reference',
        'Submitted' => 'created_at',
        'Status' => 'status',
        'Position' => 'position',
        'Designation' => 'designation_name',
        'Name' => 'name',
        'Email' => 'email',
        'Phone' => 'phone',
        'Location' => 'location',
        'Experience' => 'experience',
        'Current Role' => 'current_role',
        'Notice Period' => 'notice_period',
        'LinkedIn' => 'linkedin_url',
        'Portfolio' => 'portfolio_url',
        'Assigned To' => 'assignee',
        'Archived' => 'archived_at',
    ];

    public function __invoke(JobApplicationFilterRequest $request): StreamedResponse
    {
        Gate::authorize('export', JobApplication::class);

        $query = JobApplication::query()
            ->when($request->wantsArchived(), fn ($query) => $query->archived(), fn ($query) => $query->unarchived())
            ->with('assignee:id,name')
            ->filter($request->filters())
            ->orderBy($request->sortColumn(), $request->sortDirection())
            ->orderByDesc('id');

        Log::info('Job applications exported', [
            'user_id' => $request->user()->id,
            'filters' => $request->filters(),
        ]);

        $filename = 'job-applications-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM so Excel opens names correctly.
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_keys(self::COLUMNS), escape: '');

            foreach ($query->lazy(500) as $application) {
                fputcsv($handle, array_map(
                    fn (string $column): string => $this->sanitize($this->value($application, $column)),
                    self::COLUMNS,
                ), escape: '');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function value(JobApplication $application, string $column): string
    {
        $timezone = config('app.timezone');

        return match ($column) {
            'reference' => $application->reference(),
            'created_at' => $application->created_at?->timezone($timezone)->format('Y-m-d H:i') ?? '',
            'archived_at' => $application->archived_at?->timezone($timezone)->format('Y-m-d H:i') ?? '',
            'status' => $application->status->label(),
            'position' => $application->appliedFor(),
            'experience' => (string) $application->experienceLabel(),
            'assignee' => (string) $application->assignee?->name,
            default => (string) $application->{$column},
        };
    }

    /**
     * Neutralise spreadsheet formula injection: a candidate-supplied value that
     * starts with =, +, -, @, tab or carriage return is prefixed with a quote.
     */
    private function sanitize(string $value): string
    {
        return $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)
            ? "'".$value
            : $value;
    }
}
