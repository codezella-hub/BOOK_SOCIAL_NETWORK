<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing approval data from donations table to approval_status table
        $donations = DB::table('donations')->select('id', 'status', 'admin_notes', 'approved_at', 'approved_by')->get();
        
        foreach ($donations as $donation) {
            DB::table('approval_status')->insert([
                'donation_id' => $donation->id,
                'status' => $donation->status ?? 'pending',
                'admin_notes' => $donation->admin_notes,
                'approved_at' => $donation->approved_at,
                'approved_by' => $donation->approved_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move data back from approval_status to donations table
        $approvalStatuses = DB::table('approval_status')->get();
        
        foreach ($approvalStatuses as $approval) {
            DB::table('donations')
                ->where('id', $approval->donation_id)
                ->update([
                    'status' => $approval->status,
                    'admin_notes' => $approval->admin_notes,
                    'approved_at' => $approval->approved_at,
                    'approved_by' => $approval->approved_by,
                ]);
        }
        
        // Clear the approval_status table
        DB::table('approval_status')->truncate();
    }
};
