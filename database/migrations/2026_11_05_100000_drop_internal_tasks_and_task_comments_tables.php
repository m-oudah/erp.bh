<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('internal_tasks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed as tasks have been permanently removed per user request
    }
};

