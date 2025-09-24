<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanOffersFromRTFSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data from uraloan1.rtf
        // Headers: check_number, first_name, middle_name, last_name, vote_code, vote_name, 
        // application_number, loan_number, amount, initial_balance, ded_balance_amount
        
        $loanData = [
            [
                'check_number' => '111779080',
                'first_name' => 'Gaudence',
                'middle_name' => 'Majaliwa',
                'last_name' => 'Paul',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1723048897265',
                'loan_number' => 'URL000001',
                'amount' => 277295.28,
                'initial_balance' => 1331017344.00,
                'ded_balance_amount' => 1329353600.00,
            ],
            [
                'check_number' => '113257560',
                'first_name' => 'Kelvin',
                'middle_name' => 'William',
                'last_name' => 'Kebacho',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1732951900105',
                'loan_number' => 'URL000002',
                'amount' => 88849.00,
                'initial_balance' => 113257560.00,
                'ded_balance_amount' => 113079864.00,
            ],
            [
                'check_number' => '8716789',
                'first_name' => 'Camillus',
                'middle_name' => 'Mongoso',
                'last_name' => 'Wambura',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'ESUR20190126100103',
                'loan_number' => 'URL000003',
                'amount' => 1625000.00,
                'initial_balance' => 78000000.00,
                'ded_balance_amount' => 52000000.00,
            ],
            [
                'check_number' => '8891648',
                'first_name' => 'Salmin',
                'middle_name' => 'Kassim',
                'last_name' => 'Shelimoh',
                'vote_code' => '91',
                'vote_name' => 'Drug Control And Enforcement Authority (DCEA)',
                'application_number' => 'HHR1734684487620',
                'loan_number' => 'URL000004',
                'amount' => 1428918.90,
                'initial_balance' => 52869996.00,
                'ded_balance_amount' => 51441080.00,
            ],
            [
                'check_number' => '8675338',
                'first_name' => 'Mohamed',
                'middle_name' => 'Haji',
                'last_name' => 'Hassan',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1720016444350',
                'loan_number' => 'URL000005',
                'amount' => 1224523.40,
                'initial_balance' => 58777120.00,
                'ded_balance_amount' => 50205456.00,
            ],
            [
                'check_number' => '9449699',
                'first_name' => 'Moses',
                'middle_name' => 'Abraham',
                'last_name' => 'Mziray',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1736169630871',
                'loan_number' => 'URL000006',
                'amount' => 1059936.90,
                'initial_balance' => 50876972.00,
                'ded_balance_amount' => 49817032.00,
            ],
            [
                'check_number' => '9516207',
                'first_name' => 'Egyne',
                'middle_name' => 'Emmanuel',
                'last_name' => 'Marandu',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1738086250161',
                'loan_number' => 'URL000007',
                'amount' => 980408.70,
                'initial_balance' => 47059616.00,
                'ded_balance_amount' => 47059616.00,
            ],
            [
                'check_number' => '8851149',
                'first_name' => 'Allute',
                'middle_name' => 'Yusufu',
                'last_name' => 'Makita',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1735814720027',
                'loan_number' => 'URL000008',
                'amount' => 1295358.10,
                'initial_balance' => 46632892.00,
                'ded_balance_amount' => 45337532.00,
            ],
            [
                'check_number' => '110686235',
                'first_name' => 'Nuru',
                'middle_name' => 'Raphael',
                'last_name' => 'Letara',
                'vote_code' => 'TR133',
                'vote_name' => 'Jakaya Kikwete Cardiac Institute (JKCI)',
                'application_number' => 'HHR1716546836197',
                'loan_number' => 'URL000009',
                'amount' => 1132354.00,
                'initial_balance' => 54353036.00,
                'ded_balance_amount' => 45294204.00,
            ],
            [
                'check_number' => '8898854',
                'first_name' => 'Rafael',
                'middle_name' => 'Josephat',
                'last_name' => 'Rutahiwa',
                'vote_code' => 'TR97',
                'vote_name' => 'e- Government Authority (eGA)',
                'application_number' => 'ESUR20190126110152',
                'loan_number' => 'URL000010',
                'amount' => 1430000.00,
                'initial_balance' => 68640000.00,
                'ded_balance_amount' => 44330000.00,
            ],
            [
                'check_number' => '9326154',
                'first_name' => 'Iddy',
                'middle_name' => 'Mustapha',
                'last_name' => 'Kiyogomo',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1735806014037',
                'loan_number' => 'URL000011',
                'amount' => 884553.50,
                'initial_balance' => 42458568.00,
                'ded_balance_amount' => 41574016.00,
            ],
            [
                'check_number' => '8784586',
                'first_name' => 'Abubakar',
                'middle_name' => 'Hussein',
                'last_name' => 'Kunga',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1737094567788',
                'loan_number' => 'URL000012',
                'amount' => 882183.50,
                'initial_balance' => 42344808.00,
                'ded_balance_amount' => 41462624.00,
            ],
            [
                'check_number' => '8764997',
                'first_name' => 'Justin',
                'middle_name' => 'Majura',
                'last_name' => 'Masejo',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1717499913219',
                'loan_number' => 'URL000013',
                'amount' => 971718.50,
                'initial_balance' => 46642488.00,
                'ded_balance_amount' => 38868740.00,
            ],
            [
                'check_number' => '9327221',
                'first_name' => 'Richard',
                'middle_name' => 'Thadei',
                'last_name' => 'Mchomvu',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1711362410850',
                'loan_number' => 'URL000014',
                'amount' => 1016466.70,
                'initial_balance' => 48790400.00,
                'ded_balance_amount' => 38625732.00,
            ],
            [
                'check_number' => '9521454',
                'first_name' => 'Alex',
                'middle_name' => 'Sospeter',
                'last_name' => 'Mkama',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1736259596953',
                'loan_number' => 'URL000015',
                'amount' => 817138.94,
                'initial_balance' => 39222668.00,
                'ded_balance_amount' => 38405528.00,
            ],
            [
                'check_number' => '9452325',
                'first_name' => 'Modesta',
                'middle_name' => 'Masatu',
                'last_name' => 'Mgini',
                'vote_code' => '28',
                'vote_name' => 'Tanzania Police Force',
                'application_number' => 'HHR1735642144000',
                'loan_number' => 'URL000016',
                'amount' => 802128.60,
                'initial_balance' => 38502176.00,
                'ded_balance_amount' => 37700044.00,
            ],
        ];

        foreach ($loanData as $loan) {
            // Map the data from RTF to loan_offers table columns
            DB::table('loan_offers')->updateOrInsert(
                ['application_number' => $loan['application_number']], // Use application_number as unique identifier
                [
                    'check_number' => $loan['check_number'],
                    'first_name' => $loan['first_name'],
                    'middle_name' => $loan['middle_name'],
                    'last_name' => $loan['last_name'],
                    'vote_code' => $loan['vote_code'],
                    'vote_name' => $loan['vote_name'],
                    'application_number' => $loan['application_number'],
                    
                    // Map amount to requested_amount
                    'requested_amount' => $loan['amount'],
                    
                    // Add the loan_number as a custom field (you may need to add this column)
                    // 'loan_number' => $loan['loan_number'],
                    
                    // Set default values for required fields not in the RTF data
                    'sex' => 'M', // Default value, update as needed
                    'employment_date' => Carbon::now()->subYears(5),
                    'marital_status' => 'SINGLE',
                    'bank_account_number' => '0000000000',
                    'nin' => '00000000000000000000',
                    'designation_code' => 'DES001',
                    'designation_name' => 'Officer',
                    'basic_salary' => $loan['initial_balance'] / 12, // Estimate based on initial balance
                    'net_salary' => $loan['ded_balance_amount'] / 12, // Estimate
                    'one_third_amount' => $loan['amount'] / 3,
                    'total_employee_deduction' => 0,
                    'retirement_date' => intval(Carbon::now()->addYears(20)->format('Ymd')),
                    'terms_of_employment' => 'PERMANENT',
                    'desired_deductible_amount' => $loan['amount'] / 12, // Monthly deduction estimate
                    'tenure' => 12,
                    'fsp_code' => 'URA001',
                    'product_code' => 'LOAN01',
                    'interest_rate' => 10.00,
                    'processing_fee' => 1.00,
                    'insurance' => 0.50,
                    'physical_address' => 'Tanzania',
                    'email_address' => strtolower($loan['first_name']) . '.' . strtolower($loan['last_name']) . '@example.com',
                    'mobile_number' => '0700000000',
                    'loan_purpose' => 'Personal Use',
                    'swift_code' => 'NMIBTZTZ',
                    'funding' => 'URAERP',
                    'approval' => 'PENDING',
                    'status' => 'pending',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        $this->command->info('Loan offers from RTF file have been seeded successfully!');
    }
}