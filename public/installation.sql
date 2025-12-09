/**
 * 14-05-2025
 */
ALTER TABLE `account_management_fields` ADD `is_hidden_option` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0: No, 1: Yes' AFTER `required`;
Create TABLE account_management_fields_indexing
ALTER TABLE `companies` CHANGE `balance` `balance` VARCHAR(15) NULL DEFAULT '0.00';
ALTER TABLE `account_summeries` CHANGE `balance` `balance` VARCHAR(15) NOT NULL DEFAULT '0.00';

/**
 * 16-05-2025
 */
ALTER TABLE `admin_logs` ADD `table_view` TEXT NULL DEFAULT NULL COMMENT 'Store old or new data different as table format' AFTER `description`;

/**
 * 24-05-2025
 */
ALTER TABLE `account_summeries` CHANGE `document` `document` TINYINT(1) NULL DEFAULT '0' COMMENT '0: No, 1: Yes';
Create table account_summery_file_maps

/**
 * 31-05-2025
 */
ALTER TABLE `admins` ADD `session_id` VARCHAR(100) NULL DEFAULT NULL AFTER `is_assign_super_admin`;

/**
 * 12-06-2025
 */
UPDATE account_summeries SET `description` = REGEXP_REPLACE(`description`, '<[^>]*>', '');
UPDATE account_summeries SET `remarks` = REGEXP_REPLACE(`remarks`, '<[^>]*>', '');


UPDATE `account_summeries` SET `is_check_balance`= 0 WHERE `company_id` = 11 AND `payment_type` = 0;

UPDATE `account_summeries` SET `credit_amount`=0 WHERE `company_id` = 11 AND `credit_amount` IS NULL
UPDATE `account_summeries` SET `debit_amount`=0 WHERE `company_id` = 11 AND `debit_amount` IS NULL
