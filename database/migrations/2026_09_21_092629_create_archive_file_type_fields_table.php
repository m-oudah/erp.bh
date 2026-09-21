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
        Schema::create('archive_file_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_file_type_id')->constrained('archive_file_types')->onDelete('cascade');
            $table->string('field_name'); // e.g. id_no
            $table->string('field_label'); // e.g. رقم الهوية
            $table->string('field_type')->default('text'); // text, number, date, textarea
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_file_type_fields');
    }
};
