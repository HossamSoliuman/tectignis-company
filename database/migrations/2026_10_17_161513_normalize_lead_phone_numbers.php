<?php

use App\Support\PhoneNumbers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rewrites the phone numbers of existing leads in E.164 form, the way new
 * enquiries are stored. Numbers that aren't valid are left as they were.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('leads')->whereNotNull('phone')->select(['id', 'phone'])->chunkById(200, function ($leads): void {
            foreach ($leads as $lead) {
                $phone = PhoneNumbers::toE164($lead->phone, null);

                if ($phone !== null && $phone !== $lead->phone) {
                    DB::table('leads')->where('id', $lead->id)->update(['phone' => $phone]);
                }
            }
        });
    }

    /**
     * The original spacing isn't kept, and E.164 numbers still read correctly.
     */
    public function down(): void
    {
        //
    }
};
