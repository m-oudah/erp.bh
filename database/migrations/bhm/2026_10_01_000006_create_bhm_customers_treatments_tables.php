<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Customers (العملاء)
        Schema::create('bhm_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300)->nullable();
            $table->string('id_no', 20)->nullable()->index();
            $table->string('mobile', 30)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Customer Pens (قلم العملاء)
        Schema::create('bhm_customer_pens', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300)->nullable();
            $table->string('id_no', 20)->nullable()->index();
            $table->string('mobile', 30)->nullable();
            $table->string('telephone', 30)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('second_name', 100)->nullable();
            $table->string('third_name', 100)->nullable();
            $table->string('sur_name', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Subscriptions (الاشتراكات)
        Schema::create('bhm_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained('bhm_buildings')->nullOnDelete();
            $table->foreignId('building_owner_id')->nullable()->constrained('bhm_building_owners')->nullOnDelete();
            $table->string('id_number', 20)->nullable()->index();
            $table->string('subscriber_name', 300)->nullable();
            $table->integer('year')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('paid', 10, 2)->nullable();
            $table->decimal('remaining', 10, 2)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('notes', 500)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Subscription Unit Pivot
        Schema::create('bhm_subscription_unit', function (Blueprint $table) {
            $table->unsignedInteger('subscription_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
        });

        // Departments
        Schema::create('bhm_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Treatment Name Attachments
        Schema::create('bhm_treatment_name_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->timestamps();
        });

        // Treatments (المعالجات)
        Schema::create('bhm_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('bhm_departments')->nullOnDelete();
            $table->string('name', 300)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Treatment Users Pivot
        Schema::create('bhm_treatment_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained('bhm_treatments')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id'); // references users in main erp_bh
            $table->timestamps();
        });

        // Treatment Replies
        Schema::create('bhm_treatment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained('bhm_treatments')->cascadeOnDelete();
            $table->text('reply')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        // Attachment Name Treatment Pivot
        Schema::create('bhm_attachment_name_treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained('bhm_treatments')->cascadeOnDelete();
            $table->unsignedBigInteger('treatment_name_attachment_id');
            $table->foreign('treatment_name_attachment_id', 'ant_tna_fk')
                  ->references('id')->on('bhm_treatment_name_attachments')->cascadeOnDelete();
        });

        // Customer Pen Treatment Pivot
        Schema::create('bhm_customer_pen_treatment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_id')->constrained('bhm_treatments')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('bhm_customer_pens')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_customer_pen_treatment');
        Schema::dropIfExists('bhm_attachment_name_treatment');
        Schema::dropIfExists('bhm_treatment_replies');
        Schema::dropIfExists('bhm_treatment_user');
        Schema::dropIfExists('bhm_treatments');
        Schema::dropIfExists('bhm_treatment_name_attachments');
        Schema::dropIfExists('bhm_departments');
        Schema::dropIfExists('bhm_subscription_unit');
        Schema::dropIfExists('bhm_subscriptions');
        Schema::dropIfExists('bhm_customer_pens');
        Schema::dropIfExists('bhm_customers');
    }
};
