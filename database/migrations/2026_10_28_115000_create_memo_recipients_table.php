<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memo_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained('internal_memos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('status')->default('قيد المعالجة');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->unique(['memo_id', 'user_id']);
        });

        // Seed memo_recipients with existing memos' current_assignee_id
        $memos = DB::table('internal_memos')->whereNotNull('current_assignee_id')->get();
        foreach ($memos as $memo) {
            DB::table('memo_recipients')->insertOrIgnore([
                'memo_id' => $memo->id,
                'user_id' => $memo->current_assignee_id,
                'department_id' => $memo->target_department_id,
                'status' => $memo->status ?? 'قيد المعالجة',
                'created_at' => $memo->created_at ?? now(),
                'updated_at' => $memo->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('memo_recipients');
    }
};
