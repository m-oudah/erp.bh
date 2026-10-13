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
        // 1. Transaction Types (bhm_transaction_types)
        Schema::create('bhm_transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('sla_days')->default(0);
            $table->json('default_depts')->nullable();
            $table->string('responsible_dept')->nullable();
            $table->string('request_fees')->nullable();
            $table->json('required_docs')->nullable();
            $table->text('execution_steps')->nullable();
            $table->timestamps();
        });

        // 2. Transactions (bhm_transactions)
        Schema::create('bhm_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('transaction_type_id')->constrained('bhm_transaction_types')->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('bhm_customers')->onDelete('restrict');
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->onDelete('restrict');
            $table->text('details')->nullable();
            $table->enum('status', ['pending', 'returned', 'approved', 'closed'])->default('pending');
            $table->text('mayor_opinion')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Department Responses (bhm_department_responses)
        Schema::create('bhm_department_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('bhm_transactions')->onDelete('cascade');
            $table->string('department_name')->nullable();
            $table->text('response_text')->nullable();
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['pending', 'responded', 'returned'])->default('pending');
            $table->boolean('is_latest')->default(true);
            $table->integer('duration_days')->default(0);
            $table->boolean('is_delayed')->default(false);
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });

        // 4. Attachments (bhm_transaction_attachments)
        Schema::create('bhm_transaction_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('bhm_transactions')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_label')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamps();
        });

        // 5. Audit Logs (bhm_transaction_audit_logs)
        Schema::create('bhm_transaction_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('bhm_transactions')->onDelete('cascade');
            $table->string('user_name')->nullable();
            $table->string('action');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
        });

        // 6. Draft Transactions (bhm_draft_transactions)
        Schema::create('bhm_draft_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->nullable();
            $table->string('service_requested');
            $table->string('full_name');
            $table->string('national_id', 15)->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('parcel_number')->nullable();
            $table->string('plot_number')->nullable();
            $table->string('planning_file_number')->nullable();
            $table->string('water_subscription_number')->nullable();
            $table->string('property_address')->nullable();
            $table->text('details');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bhm_draft_transactions');
        Schema::dropIfExists('bhm_transaction_audit_logs');
        Schema::dropIfExists('bhm_transaction_attachments');
        Schema::dropIfExists('bhm_department_responses');
        Schema::dropIfExists('bhm_transactions');
        Schema::dropIfExists('bhm_transaction_types');
    }
};
