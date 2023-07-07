<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Carbon\Carbon;
use League\Csv\Reader;
use League\Csv\Statement;
use Illuminate\Support\Facades\DB;

class ImportProductDataFromCsv extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:csv {file : The path to the CSV file} {--test : Run in test mode}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Import data from a CSV file into the products table';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Get the path to the CSV file from the command argument
            $filePath = $this->argument('file');

            // Create a new Reader object to parse the CSV file
            $reader = Reader::createFromPath($filePath);

            // Configure the Reader options
            $reader->setDelimiter(',');
            $reader->setEnclosure('"');
            $reader->setEscape('\\');

            // Create a new Statement object to filter and process the rows
            $statement = Statement::create();

            // Get a result set containing the rows from the CSV file
            $result = $statement->process($reader);

            $totalItems = 0;
            $importedItems = 0;
            $skippedItems = 0;
            $failedItems = [];

            // Start a database transaction
            DB::beginTransaction();

            // Process each row of the CSV file
            foreach ($result as $index => $row) {
                // Skip the header row
                if ($index === 0) {
                    continue;
                }
                $totalItems++;

                // Validate the row
                if ($this->isValidRow($row)) {
                    // Extract the values from the row
                    [$productCode, $productName, $productDesc, $stock, $price, $discontinued] = $row;

                    if (($price < 5 && $stock < 10) || $price > 1000) {
                        $skippedItems++;
                        continue;
                    }

                    // Find or create the product based on the product code
                    if (!$this->option('test')) {
                        $product = [
                            'strProductCode' => $productCode,
                            'strProductName' => $productName,
                            'strProductDesc' => $productDesc,
                            'stock' => $stock,
                            'price' => $price,
                            'dtmAdded' => Carbon::now(),
                            'dtmDiscontinued' => $discontinued === 'yes' ? Carbon::now() : null,
                        ];
                        // Inserting or updating product data on the database
                        Product::updateOrCreate(['strProductCode' => $productCode], $product);
                    }

                    $importedItems++;
                } else {
                    // Store the failed row for reporting
                    $failedItems[] = implode(',', $row);
                }
            }

            // Commit the transaction
            DB::commit();

            // Report the import results
            $this->info('Total items processed: ' . $totalItems);
            $this->info('No of inserted or updated products: ' . $importedItems . ($this->option('test') ? ' (not saved on database)' : ''));
            $this->info('No of skipped products: ' . $skippedItems);
            $this->info('Failed items: ' . count($failedItems));

            if (!empty($failedItems)) {
                $this->table(['Failed Items'], array_map(function ($item) {
                    return [$item];
                }, $failedItems));
            }
        } catch (\Throwable $e) {
            // Handle any exceptions or errors that occurred during the import process
            $this->error('An error occurred during the import: ' . $e->getMessage());
        }
    }

    /**
     * Validate the row data.
     *
     * @param array $row
     * @return bool
     */
    private function isValidRow(array $row): bool
    {
        // Check if the row has the correct number of columns
        if (count($row) !== 6) {
            return false;
        }

        // Extract the values from the row
        [$productCode, $productName, $productDesc, $stock, $price, $discontinued] = $row;

        // Validate product code, name, and description
        if (empty($productCode) || empty($productName) || empty($productDesc)) {
            return false;
        }

        // Validate stock as a non-negative integer
        if (!is_numeric($stock) || $stock < 0 || intval($stock) != $stock) {
            return false;
        }

        // Validate price as a decimal number
        if (!is_numeric($price) || $price <= 0) {
            return false;
        }

        // Validate discontinued field
        if (!empty($discontinued) && !in_array($discontinued, ['yes', 'no'])) {
            return false;
        }

        return true;
    }
}
