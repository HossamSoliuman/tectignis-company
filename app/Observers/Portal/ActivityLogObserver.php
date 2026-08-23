<?php

namespace App\Observers\Portal;

use App\Models\Portal\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Writes the append-only audit trail for every portal model that declares
 * `#[ObservedBy(ActivityLogObserver::class)]`.
 *
 * Attach it to a model rather than logging from controllers: a record changed
 * by a console command, a queued job or a seeder is still recorded.
 */
class ActivityLogObserver
{
    /**
     * Never store credentials or framework bookkeeping in the diff.
     *
     * @var list<string>
     */
    private const IGNORED_ATTRIBUTES = ['password', 'remember_token', 'updated_at', 'created_at', 'completion_percent'];

    public function created(Model $model): void
    {
        $this->record($model, 'created', $this->describe($model, 'created'), [
            'after' => $this->scrub($model->getAttributes()),
        ]);
    }

    public function updated(Model $model): void
    {
        $changes = $this->scrub($model->getChanges());

        if ($changes === []) {
            return;
        }

        $before = array_intersect_key($this->scrub($model->getOriginal()), $changes);

        $this->record($model, 'updated', $this->describe($model, 'updated'), [
            'before' => $before,
            'after' => $changes,
        ]);
    }

    public function deleted(Model $model): void
    {
        $event = method_exists($model, 'isForceDeleting') && $model->isForceDeleting()
            ? 'force_deleted'
            : 'deleted';

        $this->record($model, $event, $this->describe($model, $event), [
            'before' => $this->scrub($model->getAttributes()),
        ]);
    }

    public function restored(Model $model): void
    {
        $this->record($model, 'restored', $this->describe($model, 'restored'));
    }

    /**
     * @param  array{before?: array<string, mixed>, after?: array<string, mixed>}|null  $changes
     */
    private function record(Model $model, string $event, string $description, ?array $changes = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => $model->getMorphClass(),
            'subject_id' => $model->getKey(),
            'event' => $event,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * "Task TSK-000012 updated" — falls back to the class name when the model
     * has no obvious human label.
     */
    private function describe(Model $model, string $event): string
    {
        $label = $model->getAttribute('code')
            ?? $model->getAttribute('title')
            ?? $model->getAttribute('name')
            ?? '#'.$model->getKey();

        return Str::headline(class_basename($model)).' '.$label.' '.str_replace('_', ' ', $event);
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    private function scrub(array $attributes): array
    {
        return array_diff_key($attributes, array_flip(self::IGNORED_ATTRIBUTES));
    }
}
