<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_attachment_name_treatment` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `treatment_name_attachment_id` int unsigned NOT NULL DEFAULT \'0\',
  `treatment_id` int unsigned NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `license_form_id` bigint unsigned DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `building_id` bigint DEFAULT NULL,
  `proof_of_case_id` bigint DEFAULT NULL,
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2024 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_build_financial` (
  `id` int NOT NULL AUTO_INCREMENT,
  `build_id` int NOT NULL,
  `land_area` double NOT NULL,
  `dev_fees` double NOT NULL,
  `dev_disc_val` double NOT NULL,
  `dev_disc_rat` double NOT NULL,
  `dev_per_meter` double NOT NULL,
  `last_update` datetime NOT NULL,
  `updatedBy` varchar(100) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `dev_remains` double NOT NULL,
  `fees_remains` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4125 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_building_building_material` (
  `building_id` bigint unsigned NOT NULL,
  `building_material_id` bigint unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_building_building_use` (
  `building_id` bigint unsigned NOT NULL,
  `building_use_id` bigint unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_building_owner_unit` (
  `building_owner_id` int NOT NULL,
  `unit_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_building_owners` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `building_id` int unsigned DEFAULT NULL,
  `id_card` varchar(9) NOT NULL,
  `mokalaf` int DEFAULT \'0\',
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `is_approve` tinyint NOT NULL DEFAULT \'0\',
  `building_number` varchar(255) DEFAULT NULL,
  `license_form_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `building_id_fk_bo` (`building_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9158 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_category_archive_attachments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `id_number` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `mobile` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9197 DEFAULT CHARSET=latin1');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_attachments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `required` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_discounts` (
  `id` int NOT NULL,
  `file_number` int NOT NULL,
  `discount_date` date NOT NULL,
  `discount_ratio` tinyint NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL,
  `notes` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` int NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_editor` int DEFAULT NULL,
  `last_edit_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_documents` (
  `id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `document_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_installments` (
  `id` int unsigned NOT NULL,
  `file_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `amount` int unsigned NOT NULL,
  `due_date` date NOT NULL,
  `is_paid` tinyint unsigned NOT NULL DEFAULT \'0\',
  `receipt_number` int unsigned DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_licenses` (
  `id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `system_no` int unsigned NOT NULL,
  `company_name` varchar(5000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `trade_number` int unsigned DEFAULT NULL,
  `category_id` int unsigned NOT NULL,
  `profession_id` int unsigned NOT NULL,
  `craft_status` tinyint unsigned NOT NULL,
  `zone_id` int unsigned NOT NULL,
  `address` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `block_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `parcel_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `notes` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `creation_date` date NOT NULL,
  `created_by` int unsigned NOT NULL,
  `building_number` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_editor` int unsigned DEFAULT NULL,
  `last_edit_date` date DEFAULT NULL,
  `previous_debt` int unsigned NOT NULL DEFAULT \'0\',
  `first_license_date` date DEFAULT NULL,
  `craft_close_date` date DEFAULT NULL,
  `banner_area1` decimal(10,3) unsigned NOT NULL,
  `banner_price_per_meter1` int unsigned NOT NULL,
  `banner_type1` decimal(10,3) unsigned NOT NULL,
  `banner_area2` decimal(10,3) unsigned NOT NULL,
  `banner_price_per_meter2` decimal(10,3) unsigned NOT NULL,
  `banner_type2` decimal(10,3) unsigned NOT NULL,
  `street_id` int unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_master` (
  `file_number` int unsigned NOT NULL,
  `system_no` int unsigned DEFAULT NULL,
  `company_name` varchar(5000) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `trade_number` int unsigned DEFAULT NULL,
  `category_id` int unsigned NOT NULL,
  `profession_id` int unsigned NOT NULL,
  `craft_status` tinyint unsigned NOT NULL,
  `zone_id` int unsigned NOT NULL,
  `address` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `block_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `parcel_number` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `notes` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int unsigned NOT NULL,
  `building_number` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_editor` int unsigned DEFAULT NULL,
  `last_edit_date` date DEFAULT NULL,
  `previous_debt` int unsigned NOT NULL DEFAULT \'0\',
  `first_license_date` date DEFAULT NULL,
  `craft_close_date` date DEFAULT NULL,
  `banner_area1` decimal(10,3) unsigned NOT NULL DEFAULT \'0.000\',
  `banner_price_per_meter1` int unsigned NOT NULL DEFAULT \'0\',
  `banner_type1` decimal(10,3) unsigned NOT NULL DEFAULT \'0.000\',
  `banner_area2` decimal(10,3) unsigned NOT NULL DEFAULT \'0.000\',
  `banner_price_per_meter2` decimal(10,3) unsigned NOT NULL DEFAULT \'0.000\',
  `banner_type2` decimal(10,3) unsigned NOT NULL DEFAULT \'0.000\',
  `street_id` int unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_owners` (
  `id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `full_name` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_file_receipts` (
  `id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `receipt_number` int unsigned NOT NULL,
  `receipt_date` date NOT NULL,
  `receipt_amount` int unsigned NOT NULL,
  `notes` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_editor` int unsigned DEFAULT NULL,
  `last_edit_date` date DEFAULT NULL,
  `receipt_year` int unsigned DEFAULT NULL,
  `receipt_year2` int unsigned DEFAULT NULL,
  `receipt_year3` int unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_license_owners` (
  `id` int unsigned NOT NULL,
  `license_id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `full_name` varchar(105) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_license_real_state_owners` (
  `id` int unsigned NOT NULL,
  `license_id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `created_by` int unsigned NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_craft_real_state_owners` (
  `id` int unsigned NOT NULL,
  `file_number` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `created_by` int unsigned NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_crafts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `craft_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `id_number` varchar(25) DEFAULT NULL,
  `service_id` int DEFAULT NULL,
  `building_number` int DEFAULT NULL,
  `mobile` varchar(25) DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `business_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT \'1\',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `started_at` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1062 DEFAULT CHARSET=latin1');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_currencies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `currency` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_customer_pen_treatment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT \'13\',
  `treatment_id` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `current_zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_block` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_block` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_parcel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_parcel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_field` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_project_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `craft_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `craft_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `craft_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `craft_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'in process\',
  `attachment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_current_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_new_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_id` (`treatment_id`,`created_at`),
  KEY `deleted_at` (`deleted_at`,`status`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=10004 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_customer_pens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `second_name` varchar(255) DEFAULT NULL,
  `third_name` varchar(255) DEFAULT NULL,
  `sur_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18496 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_deleted_receipts` (
  `rec_no` int NOT NULL,
  `build_id` int NOT NULL,
  `rec_val` double NOT NULL,
  `rec_type` int NOT NULL,
  `curr_type` int NOT NULL,
  `rec_date` date NOT NULL,
  `deleted_by` varchar(20) NOT NULL,
  `delete_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_development_data` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `floor_description_id` bigint unsigned NOT NULL,
  `dev_price_per_meter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_fees` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `totle_fees` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_val` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required_pay` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dev_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1860 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_license_fees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `building_id` int unsigned NOT NULL,
  `floor_number` tinyint unsigned NOT NULL,
  `floor_area` decimal(10,2) unsigned NOT NULL,
  `cost_per_meter` decimal(10,2) unsigned NOT NULL DEFAULT \'0.00\',
  `discount_amount` decimal(10,2) unsigned NOT NULL,
  `total_amount` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_license_form_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) DEFAULT \'\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=827 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_municipalities` (
  `id` int NOT NULL AUTO_INCREMENT,
  `city_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` tinyint DEFAULT NULL,
  `name_ar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_previous_owners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `building_id` bigint unsigned DEFAULT NULL,
  `id_card` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mokalaf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `third_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sur_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_proof_of_cases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `building_id` bigint DEFAULT NULL,
  `day` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hours` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT \'0\',
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_regulatory_disclosure_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `building_id` bigint unsigned DEFAULT NULL,
  `isproperty` tinyint DEFAULT NULL,
  `isorted` tinyint DEFAULT NULL,
  `region` tinyint DEFAULT NULL,
  `location_status` tinyint DEFAULT NULL,
  `total_coupon_space` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building_area` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rebounds_front` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rebounds_back` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rebounds_right` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rebounds_left` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `construction_ratio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_floor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose_building_use` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `site_on_structural` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `passes_through_site` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `territory_regulatory_requirement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `department_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `license_form_id` bigint DEFAULT NULL,
  `trust` tinyint NOT NULL DEFAULT \'0\',
  `development_area` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=165 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_street_types` (
  `structural_street_number` int NOT NULL DEFAULT \'0\',
  `street_width` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_subscription_unit` (
  `subscription_id` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_supervisors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `second_name` varchar(30) NOT NULL,
  `third_name` varchar(30) NOT NULL,
  `sur_name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_tmp_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `extension` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6641 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_treatment_name_attachments` (
  `id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `name` varchar(2552) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `important` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_treatment_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_pen_treatment_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_treatment_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `treatment_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `order` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_unit_owners` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `building_owner_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id_fk_uo` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12226 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_unit_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` int unsigned NOT NULL,
  `id_card` varchar(9) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `third_name` varchar(50) DEFAULT NULL,
  `sur_name` varchar(50) NOT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  `dependents_count` int unsigned NOT NULL DEFAULT \'0\',
  PRIMARY KEY (`id`),
  KEY `unit_id_fk_uu` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10645 DEFAULT CHARSET=utf8mb3');

        DB::unprepared('CREATE TABLE IF NOT EXISTS `bhm_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3');


    }

    public function down(): void
    {
        Schema::dropIfExists('bhm_attachment_name_treatment');
        Schema::dropIfExists('bhm_attachments');
        Schema::dropIfExists('bhm_build_financial');
        Schema::dropIfExists('bhm_building_building_material');
        Schema::dropIfExists('bhm_building_building_use');
        Schema::dropIfExists('bhm_building_owner_unit');
        Schema::dropIfExists('bhm_building_owners');
        Schema::dropIfExists('bhm_category_archive_attachments');
        Schema::dropIfExists('bhm_clients');
        Schema::dropIfExists('bhm_craft_attachments');
        Schema::dropIfExists('bhm_craft_file_discounts');
        Schema::dropIfExists('bhm_craft_file_documents');
        Schema::dropIfExists('bhm_craft_file_installments');
        Schema::dropIfExists('bhm_craft_file_licenses');
        Schema::dropIfExists('bhm_craft_file_master');
        Schema::dropIfExists('bhm_craft_file_owners');
        Schema::dropIfExists('bhm_craft_file_receipts');
        Schema::dropIfExists('bhm_craft_license_owners');
        Schema::dropIfExists('bhm_craft_license_real_state_owners');
        Schema::dropIfExists('bhm_craft_real_state_owners');
        Schema::dropIfExists('bhm_crafts');
        Schema::dropIfExists('bhm_currencies');
        Schema::dropIfExists('bhm_customer_pen_treatment');
        Schema::dropIfExists('bhm_customer_pens');
        Schema::dropIfExists('bhm_deleted_receipts');
        Schema::dropIfExists('bhm_development_data');
        Schema::dropIfExists('bhm_failed_jobs');
        Schema::dropIfExists('bhm_license_fees');
        Schema::dropIfExists('bhm_license_form_replies');
        Schema::dropIfExists('bhm_migrations');
        Schema::dropIfExists('bhm_model_has_permissions');
        Schema::dropIfExists('bhm_model_has_roles');
        Schema::dropIfExists('bhm_municipalities');
        Schema::dropIfExists('bhm_password_resets');
        Schema::dropIfExists('bhm_permissions');
        Schema::dropIfExists('bhm_personal_access_tokens');
        Schema::dropIfExists('bhm_previous_owners');
        Schema::dropIfExists('bhm_proof_of_cases');
        Schema::dropIfExists('bhm_regulatory_disclosure_reports');
        Schema::dropIfExists('bhm_role_has_permissions');
        Schema::dropIfExists('bhm_roles');
        Schema::dropIfExists('bhm_street_types');
        Schema::dropIfExists('bhm_subscription_unit');
        Schema::dropIfExists('bhm_supervisors');
        Schema::dropIfExists('bhm_tmp_files');
        Schema::dropIfExists('bhm_treatment_name_attachments');
        Schema::dropIfExists('bhm_treatment_replies');
        Schema::dropIfExists('bhm_treatment_user');
        Schema::dropIfExists('bhm_unit_owners');
        Schema::dropIfExists('bhm_unit_users');
        Schema::dropIfExists('bhm_users');

    }
};