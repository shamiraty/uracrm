<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentDestinationSeeder extends Seeder
{
    public function run()
    {
        $destinations = [
            // Banks
            ['name' => 'NMB Bank', 'code' => 'NMB', 'type' => 'BANK'],
            ['name' => 'CRDB Bank Limited', 'code' => 'CRDB', 'type' => 'BANK'],
            ['name' => 'NBC Bank Limited', 'code' => 'NBC', 'type' => 'BANK'],
            ['name' => 'Bank of Tanzania', 'code' => 'BOT', 'type' => 'BANK'],
            ['name' => 'People Bank of Zanzibar', 'code' => 'PBZ', 'type' => 'BANK'],
            ['name' => 'Standard Chartered', 'code' => 'STANCHART', 'type' => 'BANK'],
            ['name' => 'Stanbic Bank', 'code' => 'STANBIC', 'type' => 'BANK'],
            ['name' => 'Citibank', 'code' => 'CITI', 'type' => 'BANK'],
            ['name' => 'Bank of Africa', 'code' => 'BOA', 'type' => 'BANK'],
            ['name' => 'Diamond Trust Bank', 'code' => 'DTB', 'type' => 'BANK'],
            ['name' => 'Akiba Bank Ltd', 'code' => 'AKIBA', 'type' => 'BANK'],
            ['name' => 'Exim Bank', 'code' => 'EXIM', 'type' => 'BANK'],
            ['name' => 'KILIMANJARO COOPERATIVE BANK', 'code' => 'KILI', 'type' => 'BANK'],
            ['name' => 'KENYA COMMERCIAL BANK', 'code' => 'KCB', 'type' => 'BANK'],
            ['name' => 'Habib African Ltd', 'code' => 'HABIB', 'type' => 'BANK'],
            ['name' => 'International Commercial Bank', 'code' => 'ICB', 'type' => 'BANK'],
            ['name' => 'Barclays Bank Tanzania', 'code' => 'BARCLAYS', 'type' => 'BANK'],
            ['name' => 'I & M BANK', 'code' => 'IANDM', 'type' => 'BANK'],
            ['name' => 'Commercial Bank of Africa', 'code' => 'CBA', 'type' => 'BANK'],
            ['name' => 'Dar es Salaam Community Bank', 'code' => 'DCB', 'type' => 'BANK'],
            ['name' => 'NIC BANK', 'code' => 'NIC', 'type' => 'BANK'],
            ['name' => 'Bank of Baroda', 'code' => 'BARODA', 'type' => 'BANK'],
            ['name' => 'Azania Bankcorp Limited', 'code' => 'AZANIA', 'type' => 'BANK'],
            ['name' => 'UCHUMI BANK LTD', 'code' => 'UCHUMI', 'type' => 'BANK'],
            ['name' => 'African Banking Cooperation', 'code' => 'ABC', 'type' => 'BANK'],
            ['name' => 'AccessBank', 'code' => 'ACCESS', 'type' => 'BANK'],
            ['name' => 'Bank of India', 'code' => 'BOI', 'type' => 'BANK'],
            ['name' => 'UNITED BANK FOR AFRICA (UBA)', 'code' => 'UBA', 'type' => 'BANK'],
            ['name' => 'MKOMBOZI BANK', 'code' => 'MKOMBOZI', 'type' => 'BANK'],
            ['name' => 'ECO BANK TANZANIA LTD', 'code' => 'ECOBANK', 'type' => 'BANK'],
            ['name' => 'MWANGA COMMUNITY BANK', 'code' => 'MWANGA', 'type' => 'BANK'],
            ['name' => 'FIRST NATIONAL BANK TANZANIA', 'code' => 'FNB', 'type' => 'BANK'],
            ['name' => 'AMANA BANK', 'code' => 'AMANA', 'type' => 'BANK'],
            ['name' => 'Equity Bank Tanzania', 'code' => 'EQUITY', 'type' => 'BANK'],
            ['name' => 'TPB Bank PLC', 'code' => 'TPB', 'type' => 'BANK'],
            ['name' => 'UBL BANK (TANZANIA) LTD', 'code' => 'UBL', 'type' => 'BANK'],
            ['name' => 'MAENDELEO BANK PLC', 'code' => 'MAENDELEO', 'type' => 'BANK'],
            ['name' => 'Commercial Bank Of China', 'code' => 'CHINABANK', 'type' => 'BANK'],
            ['name' => 'Tanzania Investments Bank', 'code' => 'TIB', 'type' => 'BANK'],
            ['name' => 'CANARA BANK', 'code' => 'CANARA', 'type' => 'BANK'],
            ['name' => 'MWALIMU COMMERCIAL BANK', 'code' => 'MWALIMU', 'type' => 'BANK'],
            ['name' => 'GT BANK (T) LTD', 'code' => 'GTBANK', 'type' => 'BANK'],
            ['name' => 'CHINA DASHENG BANK LIMITED', 'code' => 'DASHENG', 'type' => 'BANK'],
            // MNOs
            ['name' => 'VODACOM MPESA', 'code' => 'MPESA', 'type' => 'MNO'],
            ['name' => 'TIGO PESA', 'code' => 'TIGOPESA', 'type' => 'MNO'],
            ['name' => 'HALOTEL HALOPESA', 'code' => 'HALOPESA', 'type' => 'MNO'],
            ['name' => 'AIRTEL MONEY', 'code' => 'AIRTEL MONEY', 'type' => 'MNO'],
            ['name' => 'TTCL PESA', 'code' => 'TTCL PESA', 'type' => 'MNO'],
            ['name' => 'ZANTEL EASY PESA', 'code' => 'EASY PESA', 'type' => 'MNO'],
        ];

        DB::table('payment_destinations')->insert($destinations);
    }
}