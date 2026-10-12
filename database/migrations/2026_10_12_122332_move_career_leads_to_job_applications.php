<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Job applications used to be stored as leads (`source = 'career'`) with the
 * CV in the public uploads folder. This moves every one of them into the
 * Careers module (spec §27) and every CV, linked or not, onto the private
 * careers disk (spec §26.13), so nothing stays publicly downloadable.
 */
return new class extends Migration
{
    private const DISK = 'careers';

    /**
     * Lead pipeline stage => closest recruitment stage.
     *
     * @var array<string, string>
     */
    private const STATUS_MAP = [
        'new' => 'new',
        'contacted' => 'screening',
        'qualified' => 'shortlisted',
        'proposal' => 'interview',
        'won' => 'selected',
        'lost' => 'rejected',
        'spam' => 'rejected',
    ];

    /**
     * Labels the old careers form stored => experience band values.
     *
     * @var array<string, string>
     */
    private const EXPERIENCE_MAP = [
        'Fresher' => 'fresher',
        '1–2 years' => '1-2',
        '3–5 years' => '3-5',
        '5–8 years' => '5-8',
        '8+ years' => '8-plus',
    ];

    /**
     * Roles that can work applications; other assignees are dropped.
     *
     * @var list<string>
     */
    private const CAREERS_ROLES = ['admin', 'editor', 'hr'];

    private const DEFAULT_MESSAGE = 'Job application submitted from the careers page.';

    public function up(): void
    {
        DB::table('leads')->where('source', 'career')->orderBy('id')->get()
            ->each(fn (object $lead) => $this->moveLead($lead));

        $this->moveUnlinkedResumes();
    }

    /**
     * Not reversed: applications may have been worked in the Careers module
     * since, and CVs must not go back to a public folder.
     */
    public function down(): void
    {
        //
    }

    private function moveLead(object $lead): void
    {
        $details = $this->parseMessage((string) $lead->message);
        $position = Str::startsWith((string) $lead->subject, 'Application: ')
            ? trim(Str::after($lead->subject, 'Application: '))
            : null;
        $resume = $this->copyResume($lead->attachment);
        $archivedAt = $lead->deleted_at ?? ($lead->status === 'spam' ? $lead->updated_at : null);

        DB::transaction(function () use ($lead, $details, $position, $resume, $archivedAt): void {
            $applicationId = DB::table('job_applications')->insertGetId([
                'job_opening_id' => $position ? DB::table('job_openings')->where('title', $position)->value('id') : null,
                'job_title' => $position,
                'name' => $lead->name,
                'email' => (string) $lead->email,
                'phone' => Str::limit((string) $lead->phone, 30, ''),
                'location' => $details['location'],
                'experience' => $details['experience'],
                'notice_period' => $details['notice_period'],
                'cover_letter' => $details['cover_letter'],
                ...$resume,
                'status' => self::STATUS_MAP[$lead->status] ?? 'new',
                'assigned_to' => $this->careersAssignee($lead->assigned_to),
                'is_read' => (bool) $lead->is_read,
                'consented_at' => $lead->consented_at,
                'archived_at' => $archivedAt,
                'created_at' => $lead->created_at,
                'updated_at' => $lead->updated_at,
            ]);

            DB::table('job_application_activities')->insert([
                'job_application_id' => $applicationId,
                'type' => 'imported',
                'created_at' => $lead->created_at,
                'updated_at' => $lead->created_at,
            ]);

            DB::table('lead_notes')->where('lead_id', $lead->id)->orderBy('id')->get()
                ->each(fn (object $note) => DB::table('job_application_notes')->insert([
                    'job_application_id' => $applicationId,
                    'user_id' => $note->user_id,
                    'body' => $note->body,
                    'created_at' => $note->created_at,
                    'updated_at' => $note->updated_at,
                ]));

            DB::table('lead_notes')->where('lead_id', $lead->id)->delete();
            DB::table('lead_activities')->where('lead_id', $lead->id)->delete();
            DB::table('leads')->where('id', $lead->id)->delete();
        });

        if ($resume['resume_path'] !== null) {
            @unlink(public_path('uploads/'.$lead->attachment));
        }
    }

    /**
     * Split the "Label: value" lines the old form put at the top of the
     * message back into fields; whatever follows is the candidate's message.
     *
     * @return array{location: ?string, experience: ?string, notice_period: ?string, cover_letter: ?string}
     */
    private function parseMessage(string $message): array
    {
        $fields = ['Total Experience' => null, 'Current Location' => null, 'Notice Period' => null];
        $remaining = [];

        foreach (preg_split('/\R/', $message) as $line) {
            $label = Str::before($line, ': ');

            if ($remaining === [] && array_key_exists($label, $fields) && Str::contains($line, ': ')) {
                $fields[$label] = trim(Str::after($line, ': '));
            } else {
                $remaining[] = $line;
            }
        }

        $coverLetter = trim(implode("\n", $remaining));
        $experience = $fields['Total Experience'];

        return [
            'location' => $fields['Current Location'],
            'experience' => $experience !== null ? (self::EXPERIENCE_MAP[$experience] ?? $experience) : null,
            'notice_period' => $fields['Notice Period'],
            'cover_letter' => in_array($coverLetter, ['', self::DEFAULT_MESSAGE], true) ? null : $coverLetter,
        ];
    }

    /**
     * Copy a public CV onto the private disk under an unguessable name.
     *
     * @return array{resume_path: ?string, resume_name: ?string, resume_mime: ?string, resume_size: ?int}
     */
    private function copyResume(?string $attachment): array
    {
        $empty = ['resume_path' => null, 'resume_name' => null, 'resume_mime' => null, 'resume_size' => null];
        $source = $attachment ? public_path('uploads/'.$attachment) : null;

        if (! $source || ! is_file($source)) {
            return $empty;
        }

        $file = new File($source);
        $extension = Str::lower($file->getExtension()) ?: 'pdf';
        $path = Storage::disk(self::DISK)->putFileAs('resumes/imported', $file, Str::random(40).'.'.$extension);

        if (! $path) {
            return $empty;
        }

        return [
            'resume_path' => $path,
            'resume_name' => basename($attachment),
            'resume_mime' => $file->getMimeType(),
            'resume_size' => $file->getSize(),
        ];
    }

    /**
     * CVs left behind by leads deleted before soft deletes existed.
     */
    private function moveUnlinkedResumes(): void
    {
        $folder = public_path('uploads/resumes');

        if (! is_dir($folder)) {
            return;
        }

        foreach (glob($folder.'/*') ?: [] as $source) {
            if (is_file($source) && Storage::disk(self::DISK)->putFileAs('resumes/unlinked', new File($source), basename($source))) {
                @unlink($source);
            }
        }

        @rmdir($folder);
    }

    private function careersAssignee(?int $userId): ?int
    {
        if ($userId === null) {
            return null;
        }

        return DB::table('users')->where('id', $userId)->whereIn('role', self::CAREERS_ROLES)->exists() ? $userId : null;
    }
};
