<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bhm_buildings', function (Blueprint $table) {
            $table->unsignedInteger('ownership_type')->nullable();
            $table->unsignedInteger('building_type')->nullable();
            $table->string('sessionDate', 10)->nullable();
            $table->unsignedBigInteger('building_finish_id')->nullable();
            $table->string('general_condition', 255)->nullable();
            $table->unsignedInteger('external_condition')->nullable();
            $table->unsignedInteger('escape_staircase')->nullable();
            $table->unsignedInteger('waterNetwork')->nullable();
            $table->unsignedInteger('sewageNetwork')->nullable();
            $table->string('area', 255)->nullable();
            $table->tinyInteger('status')->default('0');
            $table->string('image', 255)->nullable();
        });

        Schema::table('bhm_craft_categories', function (Blueprint $table) {
            $table->date('update_at')->nullable();
            $table->date('deleted_at')->nullable();
        });

        Schema::table('bhm_customers', function (Blueprint $table) {
            $table->string('id_number', 50)->nullable();
            $table->string('customer_number', 255)->nullable();
            $table->string('notes', 255)->nullable();
        });

        Schema::table('bhm_departments', function (Blueprint $table) {
            $table->integer('manger_id')->nullable();
            $table->string('inner_telephone', 255)->nullable();
            $table->date('deleted_at')->nullable();
        });

        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->integer('municipality_id')->nullable();
            $table->integer('street_id')->nullable();
            $table->string('building_number', 7)->nullable();
            $table->string('floor_number', 20)->nullable();
            $table->string('unit_number', 20)->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('subzone_id')->nullable();
            $table->string('parcel_number', 20)->nullable();
            $table->string('block_number', 20)->nullable();
            $table->string('job_use_name', 50)->nullable();
            $table->string('old_job_number', 20)->nullable();
            $table->string('job_location', 100)->nullable();
            $table->string('job_street', 100)->nullable();
            $table->string('job_phone', 20)->nullable();
            $table->string('job_mobile', 20)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('facebook', 200)->nullable();
            $table->string('instagram', 200)->nullable();
            $table->string('twitter', 200)->nullable();
            $table->integer('ownership_type')->nullable();
            $table->string('ownership_note', 200)->nullable();
            $table->integer('work_state')->nullable();
            $table->string('job_description', 1000)->nullable();
            $table->boolean('has_branch')->nullable()->default('0');
            $table->integer('branch_number')->nullable();
            $table->boolean('is_main_branch')->nullable()->default('0');
            $table->integer('male_employee')->nullable();
            $table->integer('female_employee')->nullable();
            $table->integer('info_from')->nullable()->default('0');
            $table->string('info_from_note', 200)->nullable();
            $table->integer('info_result')->nullable()->default('0');
            $table->string('refuse_reason', 200)->nullable();
            $table->integer('collector_id')->nullable();
            $table->integer('supervisor_id')->nullable();
            $table->date('get_date')->nullable();
            $table->date('creation_date')->nullable();
            $table->string('craft_number', 50)->nullable();
            $table->unsignedBigInteger('bulding_id')->nullable();
            $table->string('file_number', 255)->nullable();
            $table->string('license_number', 255)->nullable();
            $table->tinyInteger('type_property')->nullable();
            $table->tinyInteger('isActive')->nullable()->default('2');
            $table->integer('craft_category_id')->nullable();
            $table->integer('craft_type_id')->nullable();
            $table->integer('approved')->nullable();
        });

        Schema::table('bhm_economical_owners', function (Blueprint $table) {
            $table->string('phone_number', 50);
            $table->char('gender', 1)->nullable();
            $table->string('mokalaf', 255)->nullable();
        });

        Schema::table('bhm_economical_sectors', function (Blueprint $table) {
            $table->string('sector_name', 200);
            $table->string('section_number', 3);
            $table->string('description', 500);
        });

        Schema::table('bhm_license_forms', function (Blueprint $table) {
            $table->string('block_number', 255)->nullable();
            $table->string('parcel_number', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->bigInteger('attachment_one')->nullable();
        });

        Schema::table('bhm_professions_categories', function (Blueprint $table) {
            $table->string('category_name', 500);
        });

        Schema::table('bhm_streets', function (Blueprint $table) {
            $table->integer('street_number');
            $table->string('street_name', 255)->nullable();
        });

        Schema::table('bhm_subscriptions', function (Blueprint $table) {
            $table->string('name', 255)->nullable();
            $table->string('customer_number', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->bigInteger('owner_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('subscription_number', 50)->nullable();
        });

        Schema::table('bhm_subzones', function (Blueprint $table) {
            $table->string('zone_number', 2);
        });

        Schema::table('bhm_treatments', function (Blueprint $table) {
            $table->date('deleted_at')->nullable();
            $table->string('user', 255)->default('0');
            $table->string('component', 230)->nullable();
            $table->string('first_level_user', 255)->nullable();
            $table->string('second_level_user', 255)->nullable();
            $table->string('third_level_user', 255)->nullable();
            $table->text('attachment')->nullable();
            $table->integer('user_id')->default('13');
            $table->string('depency', 255)->nullable();
            $table->decimal('fee_required', 10, 2)->nullable();
            $table->string('expected_time', 255)->nullable();
        });

        Schema::table('bhm_zones', function (Blueprint $table) {
            $table->string('zone_number', 2);
            $table->string('zone_name', 255)->nullable();
        });


    }

    public function down(): void
    {
        Schema::table('bhm_buildings', function (Blueprint $table) {
            $table->dropColumn(['ownership_type', 'building_type', 'sessionDate', 'building_finish_id', 'general_condition', 'external_condition', 'escape_staircase', 'waterNetwork', 'sewageNetwork', 'area', 'status', 'image']);
        });

        Schema::table('bhm_craft_categories', function (Blueprint $table) {
            $table->dropColumn(['update_at', 'deleted_at']);
        });

        Schema::table('bhm_customers', function (Blueprint $table) {
            $table->dropColumn(['id_number', 'customer_number', 'notes']);
        });

        Schema::table('bhm_departments', function (Blueprint $table) {
            $table->dropColumn(['manger_id', 'inner_telephone', 'deleted_at']);
        });

        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->dropColumn(['municipality_id', 'street_id', 'building_number', 'floor_number', 'unit_number', 'zone_id', 'subzone_id', 'parcel_number', 'block_number', 'job_use_name', 'old_job_number', 'job_location', 'job_street', 'job_phone', 'job_mobile', 'website', 'email', 'whatsapp', 'facebook', 'instagram', 'twitter', 'ownership_type', 'ownership_note', 'work_state', 'job_description', 'has_branch', 'branch_number', 'is_main_branch', 'male_employee', 'female_employee', 'info_from', 'info_from_note', 'info_result', 'refuse_reason', 'collector_id', 'supervisor_id', 'get_date', 'creation_date', 'craft_number', 'bulding_id', 'file_number', 'license_number', 'type_property', 'isActive', 'craft_category_id', 'craft_type_id', 'approved']);
        });

        Schema::table('bhm_economical_owners', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'gender', 'mokalaf']);
        });

        Schema::table('bhm_economical_sectors', function (Blueprint $table) {
            $table->dropColumn(['sector_name', 'section_number', 'description']);
        });

        Schema::table('bhm_license_forms', function (Blueprint $table) {
            $table->dropColumn(['block_number', 'parcel_number', 'region', 'attachment_one']);
        });

        Schema::table('bhm_professions_categories', function (Blueprint $table) {
            $table->dropColumn(['category_name']);
        });

        Schema::table('bhm_streets', function (Blueprint $table) {
            $table->dropColumn(['street_number', 'street_name']);
        });

        Schema::table('bhm_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['name', 'customer_number', 'address', 'mobile', 'owner_id', 'type', 'subscription_number']);
        });

        Schema::table('bhm_subzones', function (Blueprint $table) {
            $table->dropColumn(['zone_number']);
        });

        Schema::table('bhm_treatments', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'user', 'component', 'first_level_user', 'second_level_user', 'third_level_user', 'attachment', 'user_id', 'depency', 'fee_required', 'expected_time']);
        });

        Schema::table('bhm_zones', function (Blueprint $table) {
            $table->dropColumn(['zone_number', 'zone_name']);
        });


    }
};