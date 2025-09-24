<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MembersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Assuming your CSV has headers matching your database columns
        // Adjust column names if they differ in your CSV
        $memberData = [
            'ClientId' => $row['clientid'],
            'Name' => $row['name'],
            'AccountNumber' => $row['account_number'] ?? null,
            'checkNo' => $row['checkno'] ?? null,
            'Gender' => $row['gender'] ?? null,
            'phone' => $row['phone'] ?? null,
        ];

        // Find existing member by ClientId
        $member = Member::where('ClientId', $memberData['ClientId'])->first();

        if ($member) {
            // Check if data has changed
            $isChanged = false;
            foreach ($memberData as $key => $value) {
                if (array_key_exists($key, $member->toArray()) && $member->{$key} != $value) {
                    $isChanged = true;
                    break;
                }
            }

            if ($isChanged) {
                $member->update($memberData);
            }
            return null; // Return null if updated or skipped
        } else {
            return new Member($memberData); // Create new member
        }
    }

    public function batchSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }

    public function chunkSize(): int
    {
        return 1000; // Read 1000 rows into memory at a time
    }
}