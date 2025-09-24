-- Emergency Rollback Script for Normalized Loan System
-- Use this script if you need to quickly rollback the normalized structure
-- WARNING: This will remove all data from the new tables

-- Step 1: Backup current data before rollback
-- Run these commands first to save any data:
/*
SELECT * FROM loan_offer_approvals INTO OUTFILE '/tmp/loan_offer_approvals_backup.csv';
SELECT * FROM loan_disbursements INTO OUTFILE '/tmp/loan_disbursements_backup.csv';
SELECT * FROM loan_offer_topups INTO OUTFILE '/tmp/loan_offer_topups_backup.csv';
*/

-- Step 2: Remove foreign key constraints
ALTER TABLE loan_offers DROP FOREIGN KEY IF EXISTS loan_offers_bank_id_foreign;
ALTER TABLE loan_offer_approvals DROP FOREIGN KEY IF EXISTS loan_offer_approvals_loan_offer_id_foreign;
ALTER TABLE loan_offer_approvals DROP FOREIGN KEY IF EXISTS loan_offer_approvals_approved_by_foreign;
ALTER TABLE loan_offer_approvals DROP FOREIGN KEY IF EXISTS loan_offer_approvals_rejected_by_foreign;
ALTER TABLE loan_disbursements DROP FOREIGN KEY IF EXISTS loan_disbursements_loan_offer_id_foreign;
ALTER TABLE loan_disbursements DROP FOREIGN KEY IF EXISTS loan_disbursements_bank_id_foreign;
ALTER TABLE loan_disbursements DROP FOREIGN KEY IF EXISTS loan_disbursements_disbursed_by_foreign;
ALTER TABLE loan_offer_topups DROP FOREIGN KEY IF EXISTS loan_offer_topups_original_loan_id_foreign;
ALTER TABLE loan_offer_topups DROP FOREIGN KEY IF EXISTS loan_offer_topups_new_loan_id_foreign;

-- Step 3: Drop the new columns from loan_offers (if they were added)
ALTER TABLE loan_offers DROP COLUMN IF EXISTS bank_id;
ALTER TABLE loan_offers DROP COLUMN IF EXISTS loan_type;

-- Step 4: Drop the new tables
DROP TABLE IF EXISTS loan_offer_topups;
DROP TABLE IF EXISTS loan_disbursements;
DROP TABLE IF EXISTS loan_offer_approvals;
-- Note: Keep banks table as it might be used elsewhere

-- Step 5: Reset migration records
DELETE FROM migrations WHERE migration IN (
    '2025_08_31_224531_create_loan_offer_approvals_table',
    '2025_08_31_224719_create_loan_disbursements_table',
    '2025_08_31_224758_create_loan_offer_topups_table',
    '2025_08_31_234258_add_channel_tracking_to_loan_disbursements_table',
    '2025_08_31_235000_add_bank_relationship_to_loan_offers_table'
);

-- Step 6: Verify original structure is intact
DESCRIBE loan_offers;

-- Step 7: Clear Laravel caches after running this script
-- Run these commands:
-- php artisan cache:clear
-- php artisan config:clear
-- php artisan route:clear

-- End of rollback script