<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $filePath = 'C:\laragon\www\uraerp\public\apidoc\DED_URA SACCOS LTD.xlsx';
    
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Get the highest row and column numbers
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();
    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    
    echo "Excel File Structure:\n";
    echo str_repeat("=", 60) . "\n";
    echo "Total Rows: $highestRow\n";
    echo "Total Columns: $highestColumnIndex\n\n";
    
    // Get headers (assuming first row)
    echo "Column Headers:\n";
    echo str_repeat("-", 40) . "\n";
    for ($col = 1; $col <= $highestColumnIndex; $col++) {
        $value = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        echo "Column $colLetter (Index " . ($col-1) . "): $value\n";
    }
    
    // Show first 5 data rows
    echo "\nFirst 5 Data Rows:\n";
    echo str_repeat("-", 40) . "\n";
    
    for ($row = 2; $row <= min(6, $highestRow); $row++) {
        echo "\nRow $row:\n";
        for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $header = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
            $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
            if (!empty($header) && !empty($value)) {
                echo "  $header: $value\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}