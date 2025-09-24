<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSwiftCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['swift_code' => 'ACTZTZTZ', 'name' => 'ACCESSBANK TANZANIA LTD', 'short_name' => 'ACCESS'],
            ['swift_code' => 'ADVBTZTZ', 'name' => 'ADVANS BANK TANZANIA', 'short_name' => 'ADVANS'],
            ['swift_code' => 'AKCOTZTZ', 'name' => 'AKIBA COMMERCIAL BANK PLC', 'short_name' => 'AKIBA'],
            ['swift_code' => 'AMNNTZTZ', 'name' => 'AMANA BANK LIMITED', 'short_name' => 'AMANA'],
            ['swift_code' => 'AZANTZTZ', 'name' => 'AZANIA BANK LIMITED', 'short_name' => 'AZANIA'],
            ['swift_code' => 'BARBTZTZ', 'name' => 'BANK OF BARODA (TANZANIA) LTD', 'short_name' => 'BOB'],
            ['swift_code' => 'BARCTZTZ', 'name' => 'ABSA BANK TANZANIA LIMITED', 'short_name' => 'ABSA'],
            ['swift_code' => 'BKIDTZTZ', 'name' => 'BANK OF INDIA (TANZANIA) LIMITED', 'short_name' => 'BOI'],
            ['swift_code' => 'BKMYTZTZ', 'name' => 'INTERNATIONAL COMMERCIAL BANK (TANZANIA) LIMITED', 'short_name' => 'ICB'],
            ['swift_code' => 'CBAFTZTZ', 'name' => 'COMMERCIAL BANK OF AFRICA (TANZANIA) LTD', 'short_name' => 'CBA'],
            ['swift_code' => 'CDSHTZTZ', 'name' => 'China Dasheng Bank Ltd', 'short_name' => 'CDB'],
            ['swift_code' => 'CITITZTZ', 'name' => 'CITIBANK TANZANIA LTD', 'short_name' => 'CITI'],
            ['swift_code' => 'CNRBTZTZ', 'name' => 'CANARA BANK (TANZANIA) LTD', 'short_name' => 'CANARA'],
            ['swift_code' => 'CORUTZTZ', 'name' => 'CRDB BANK LIMITED', 'short_name' => 'CRDB'],
            ['swift_code' => 'DASUTZTZ', 'name' => 'DAR ES SALAAM COMMUNITY BANK LTD', 'short_name' => 'DCB'],
            ['swift_code' => 'DSTXTZTZ', 'name' => 'DAR ES SALAAM STOCK EXCHANGE', 'short_name' => 'DSE'],
            ['swift_code' => 'DTKETZTZ', 'name' => 'DIAMOND TRUST BANK TANZANIA LTD', 'short_name' => 'DTB'],
            ['swift_code' => 'ECOCTZTZ', 'name' => 'ECOBANK TANZANIA LIMITED', 'short_name' => 'ECOBANK'],
            ['swift_code' => 'EQBLTZTZ', 'name' => 'EQUITY BANK TANZANIA LIMITED', 'short_name' => 'EQUITY'],
            ['swift_code' => 'EUAFTZTZ', 'name' => 'BANK OF AFRICA TANZANIA LIMITED', 'short_name' => 'BOA'],
            ['swift_code' => 'EXTNTZTZ', 'name' => 'EXIMBANK (TANZANIA) LTD', 'short_name' => 'EXIM'],
            ['swift_code' => 'FIRNTZTX', 'name' => 'FIRST NATIONAL BANK OF TANZANIA', 'short_name' => 'FNB'],
            ['swift_code' => 'FMBZTZTX', 'name' => 'AFRICAN BANKING CORPORATION TANZANIA LIMITED', 'short_name' => 'ABC'],
            ['swift_code' => 'FNMITZTZ', 'name' => 'FINCA MICROFINANCE BANK LIMITED', 'short_name' => 'FINCA'],
            ['swift_code' => 'GTBITZTZ', 'name' => 'GUARANTY TRUST BANK TANZANIA LTD', 'short_name' => 'GTB'],
            ['swift_code' => 'HABLTZTZ', 'name' => 'HABIB AFRICAN BANK', 'short_name' => 'HABIB'],
            ['swift_code' => 'IMBLTZTZ', 'name' => 'I AND M BANK (T) LIMITED', 'short_name' => 'IM'],
            ['swift_code' => 'KCBLTZTZ', 'name' => 'KCB BANK TANZANIA LIMITED', 'short_name' => 'KCB'],
            ['swift_code' => 'MBTLTZTZ', 'name' => 'MAENDELEO BANK LTD', 'short_name' => 'MAENDELEO'],
            ['swift_code' => 'MKCBTZTZ', 'name' => 'MKOMBOZI COMMERCIAL BANK LTD', 'short_name' => 'MKOMBOZI'],
            ['swift_code' => 'MUOBTZTZ', 'name' => 'MUCOBA BANK PLC', 'short_name' => 'MUCOBA'],
            ['swift_code' => 'MWCBTZTZ', 'name' => 'MWANGA COMMUNITY BANK LTD', 'short_name' => 'MWANGA'],
            ['swift_code' => 'MWCOTZTZ', 'name' => 'MWALIMU COMMERCIAL BANK PLC', 'short_name' => 'MWALIMU'],
            ['swift_code' => 'NLCBTZTX', 'name' => 'NATIONAL BANK OF COMMERCE, THE', 'short_name' => 'NBC'],
            ['swift_code' => 'NMIBTZTZ', 'name' => 'NMB BANK PLC', 'short_name' => 'NMB'],
            ['swift_code' => 'PBZATZTZ', 'name' => 'PEOPLES BANK OF ZANZIBAR, THE', 'short_name' => 'PBZ'],
            ['swift_code' => 'SBICTZTX', 'name' => 'STANBIC BANK TANZANIA LIMITED', 'short_name' => 'STANBIC'],
            ['swift_code' => 'SCBLTZTX', 'name' => 'STANDARD CHARTERED BANK TANZANIA LTD', 'short_name' => 'SCB'],
            ['swift_code' => 'TANZTZTX', 'name' => 'BANK OF TANZANIA', 'short_name' => 'BOT'],
            ['swift_code' => 'TAPBTZTZ', 'name' => 'TANZANIA COMMERCIAL BANK PLC', 'short_name' => 'TCB'],
            ['swift_code' => 'TARATZTZ', 'name' => 'TANZANIA REVENUE AUTHORITY', 'short_name' => 'TRA'],
            ['swift_code' => 'TZADTZTZ', 'name' => 'TANZANIA AGRICULTURAL DEVELOPMENT BANK', 'short_name' => 'TADB'],
            ['swift_code' => 'UCCTTZTZ', 'name' => 'UCHUMI COMMERCIAL BANK LTD', 'short_name' => 'UCHUMI'],
            ['swift_code' => 'UNAFTZTZ', 'name' => 'UNITED BANK FOR AFRICA (TANZANIA) LIMITED', 'short_name' => 'UBA'],
            ['swift_code' => 'YETMTZTZ', 'name' => 'YETU MICROFINANCE BANK PLC', 'short_name' => 'YETU'],
        ];
        
        foreach ($banks as $bank) {
            Bank::updateOrCreate(
                ['swift_code' => $bank['swift_code']],
                [
                    'name' => $bank['name'],
                    'short_name' => $bank['short_name'],
                    'is_active' => true
                ]
            );
        }
        
        $this->command->info('Bank SWIFT codes seeded successfully!');
    }
}