-- Fix column sizes in loan_offers table
-- Run this SQL directly in your database

-- Check and add/modify fsp_reference_number column
ALTER TABLE loan_offers 
MODIFY COLUMN fsp_reference_number VARCHAR(20) NULL;

-- Check and add/modify payment_reference_number column  
ALTER TABLE loan_offers
MODIFY COLUMN payment_reference_number VARCHAR(50) NULL;

-- Check and add/modify end_date_str column
ALTER TABLE loan_offers
MODIFY COLUMN end_date_str VARCHAR(8) NULL;

-- Add columns if they don't exist
ALTER TABLE loan_offers
ADD COLUMN IF NOT EXISTS fsp_reference_number VARCHAR(20) NULL,
ADD COLUMN IF NOT EXISTS payment_reference_number VARCHAR(50) NULL,
ADD COLUMN IF NOT EXISTS final_payment_date DATETIME NULL,
ADD COLUMN IF NOT EXISTS last_deduction_date DATETIME NULL,
ADD COLUMN IF NOT EXISTS last_pay_date DATETIME NULL,
ADD COLUMN IF NOT EXISTS end_date_str VARCHAR(8) NULL;