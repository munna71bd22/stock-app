<?php

namespace Tests\Unit;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ImportProductDataFromCsvTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_data_from_csv_file_into_products_table()
    {
        // Create a test CSV file
        $csvFilePath = storage_path('csv/test_stock.csv');
        $csvContent = <<<'CSV'
                        ProductCode,ProductName,ProductDesc,Stock,Price,Discontinued
                        P001,Product 1,Description 1,10,19.99,no
                        P002,Product 2,Description 2,5,9.99,yes
                        P003,Product 3,Description 3,20,29.99,no
                        CSV;

        file_put_contents($csvFilePath, $csvContent);

        // Run the import command
        Artisan::call('import:csv', [
            'file' => $csvFilePath,
        ]);

        $output = Artisan::output();


        // Assert that the products are imported correctly
        $this->assertDatabaseCount('tblProductData', 3);
        $this->assertDatabaseHas('tblProductData', [
            'strProductCode' => 'P001',
            'strProductName' => 'Product 1',
            'strProductDesc' => 'Description 1',
            'stock' => 10,
            'price' => 19.99,
            'dtmDiscontinued' => null,
        ]);
        $this->assertDatabaseHas('tblProductData', [
            'strProductCode' => 'P002',
            'strProductName' => 'Product 2',
            'strProductDesc' => 'Description 2',
            'stock' => 5,
            'price' => 9.99,
            'dtmDiscontinued' => now(),
        ]);
        $this->assertDatabaseHas('tblProductData', [
            'strProductCode' => 'P003',
            'strProductName' => 'Product 3',
            'strProductDesc' => 'Description 3',
            'stock' => 20,
            'price' => 29.99,
            'dtmDiscontinued' => null,
        ]);

        // Clean up the test file
        unlink($csvFilePath);
    }
}
