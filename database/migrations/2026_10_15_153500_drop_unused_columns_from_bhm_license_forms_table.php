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
        Schema::table('bhm_license_forms', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'second_name',
                'third_name',
                'sur_name',
                'id_card',
                'phone',
                'attachment_one_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bhm_license_forms', function (Blueprint $table) {
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->string('id_card', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->unsignedBigInteger('attachment_one_id')->nullable();
        });
    }
};
