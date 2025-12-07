-- Add invoice_id column to rise_acc_account_history table
-- This column stores the invoice ID linked to vendor bills in accounting history

ALTER TABLE `rise_acc_account_history` 
ADD COLUMN `invoice_id` INT NULL DEFAULT NULL 
AFTER `rel_type`;

-- Add index for better query performance when filtering by invoice_id
CREATE INDEX `idx_invoice_id` ON `rise_acc_account_history` (`invoice_id`);

