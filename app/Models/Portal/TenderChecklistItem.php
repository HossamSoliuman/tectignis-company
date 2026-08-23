<?php

namespace App\Models\Portal;

use App\Enums\Portal\ChecklistItemStatus;
use App\Observers\Portal\ActivityLogObserver;
use App\Observers\Portal\TenderProgressObserver;
use Database\Factories\Portal\TenderChecklistItemFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One eligibility requirement on a tender (§9), seeded from the standard
 * template and then answered by whoever owns it.
 */
#[ObservedBy([ActivityLogObserver::class, TenderProgressObserver::class])]
class TenderChecklistItem extends Model
{
    /** @use HasFactory<TenderChecklistItemFactory> */
    use HasFactory;

    protected $table = 'portal_tender_checklist_items';

    protected $fillable = [
        'tender_id',
        'requirement',
        'category',
        'status',
        'responsible_employee_id',
        'remarks',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'status' => ChecklistItemStatus::class,
            'sort_order' => 'integer',
        ];
    }

    /** @return BelongsTo<Tender, $this> */
    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function responsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id');
    }
}
