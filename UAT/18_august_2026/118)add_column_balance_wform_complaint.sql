ALTER TABLE `w_form`
ADD COLUMN `balance` VARCHAR(50) NULL DEFAULT NULL AFTER `individual_acct_no`;

ALTER TABLE `complaint`
ADD COLUMN `balance` VARCHAR(50) NULL DEFAULT NULL AFTER `individual_acct_no`;