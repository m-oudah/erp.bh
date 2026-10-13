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
        // 1. archive_files
        Schema::table('archive_files', function (Blueprint $table) {
            $table->index('created_at', 'archive_files_created_at_idx');
            $table->fullText('file_name', 'archive_files_file_name_ft');
        });

        // 2. archive_documents
        Schema::table('archive_documents', function (Blueprint $table) {
            $table->index('document_no', 'archive_docs_document_no_idx');
            $table->index('document_type', 'archive_docs_document_type_idx');
            $table->index('created_at', 'archive_docs_created_at_idx');
            $table->fullText('document_name', 'archive_docs_name_ft');
        });

        // 3. bhm_customers
        Schema::table('bhm_customers', function (Blueprint $table) {
            $table->index('id_number', 'bhm_customers_id_number_idx');
            $table->index('mobile', 'bhm_customers_mobile_idx');
            $table->index('created_at', 'bhm_customers_created_at_idx');
            $table->fullText('name', 'bhm_customers_name_ft');
        });

        // 4. bhm_transactions
        Schema::table('bhm_transactions', function (Blueprint $table) {
            $table->index('status', 'bhm_transactions_status_idx');
            $table->index('created_at', 'bhm_transactions_created_at_idx');
            $table->fullText('details', 'bhm_transactions_details_ft');
        });

        // 5. bhm_draft_transactions
        Schema::table('bhm_draft_transactions', function (Blueprint $table) {
            $table->index('ticket_number', 'bhm_drafts_ticket_number_idx');
            $table->index('national_id', 'bhm_drafts_national_id_idx');
            $table->index('phone', 'bhm_drafts_phone_idx');
            $table->index('status', 'bhm_drafts_status_idx');
            $table->index('planning_file_number', 'bhm_drafts_planning_file_idx');
            $table->index('water_subscription_number', 'bhm_drafts_water_sub_idx');
        });

        // 6. bhm_buildings
        Schema::table('bhm_buildings', function (Blueprint $table) {
            $table->index('block_number', 'bhm_buildings_block_number_idx');
            $table->index('parcel_number', 'bhm_buildings_parcel_number_idx');
            $table->fullText('building_name', 'bhm_buildings_name_ft');
        });

        // 7. bhm_subscriptions
        Schema::table('bhm_subscriptions', function (Blueprint $table) {
            $table->index('subscription_number', 'bhm_subs_subscription_number_idx');
            $table->index('mobile', 'bhm_subs_mobile_idx');
            $table->fullText('subscriber_name', 'bhm_subs_subscriber_name_ft');
        });

        // 8. bhm_economical
        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->index('id_card', 'bhm_economical_id_card_idx');
            $table->index('file_number', 'bhm_economical_file_number_idx');
            $table->index('craft_number', 'bhm_economical_craft_number_idx');
            $table->index('license_number', 'bhm_economical_license_number_idx');
            $table->fullText(['job_formal_name', 'trade_name', 'name'], 'bhm_economical_names_ft');
        });

        // 9. bhm_building_owners
        Schema::table('bhm_building_owners', function (Blueprint $table) {
            $table->index('phone_number', 'bhm_building_owners_phone_idx');
        });

        // 10. bhm_economical_owners
        Schema::table('bhm_economical_owners', function (Blueprint $table) {
            $table->index('id_card', 'bhm_economical_owners_id_card_idx');
            $table->index('phone', 'bhm_economical_owners_phone_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bhm_economical_owners', function (Blueprint $table) {
            $table->dropIndex('bhm_economical_owners_id_card_idx');
            $table->dropIndex('bhm_economical_owners_phone_idx');
        });

        Schema::table('bhm_building_owners', function (Blueprint $table) {
            $table->dropIndex('bhm_building_owners_phone_idx');
        });

        Schema::table('bhm_economical', function (Blueprint $table) {
            $table->dropIndex('bhm_economical_id_card_idx');
            $table->dropIndex('bhm_economical_file_number_idx');
            $table->dropIndex('bhm_economical_craft_number_idx');
            $table->dropIndex('bhm_economical_license_number_idx');
            $table->dropFullText('bhm_economical_names_ft');
        });

        Schema::table('bhm_subscriptions', function (Blueprint $table) {
            $table->dropIndex('bhm_subs_subscription_number_idx');
            $table->dropIndex('bhm_subs_mobile_idx');
            $table->dropFullText('bhm_subs_subscriber_name_ft');
        });

        Schema::table('bhm_buildings', function (Blueprint $table) {
            $table->dropIndex('bhm_buildings_block_number_idx');
            $table->dropIndex('bhm_buildings_parcel_number_idx');
            $table->dropFullText('bhm_buildings_name_ft');
        });

        Schema::table('bhm_draft_transactions', function (Blueprint $table) {
            $table->dropIndex('bhm_drafts_ticket_number_idx');
            $table->dropIndex('bhm_drafts_national_id_idx');
            $table->dropIndex('bhm_drafts_phone_idx');
            $table->dropIndex('bhm_drafts_status_idx');
            $table->dropIndex('bhm_drafts_planning_file_idx');
            $table->dropIndex('bhm_drafts_water_sub_idx');
        });

        Schema::table('bhm_transactions', function (Blueprint $table) {
            $table->dropIndex('bhm_transactions_status_idx');
            $table->dropIndex('bhm_transactions_created_at_idx');
            $table->dropFullText('bhm_transactions_details_ft');
        });

        Schema::table('bhm_customers', function (Blueprint $table) {
            $table->dropIndex('bhm_customers_id_number_idx');
            $table->dropIndex('bhm_customers_mobile_idx');
            $table->dropIndex('bhm_customers_created_at_idx');
            $table->dropFullText('bhm_customers_name_ft');
        });

        Schema::table('archive_documents', function (Blueprint $table) {
            $table->dropIndex('archive_docs_document_no_idx');
            $table->dropIndex('archive_docs_document_type_idx');
            $table->dropIndex('archive_docs_created_at_idx');
            $table->dropFullText('archive_docs_name_ft');
        });

        Schema::table('archive_files', function (Blueprint $table) {
            $table->dropIndex('archive_files_created_at_idx');
            $table->dropFullText('archive_files_file_name_ft');
        });
    }
};
