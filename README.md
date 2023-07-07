# CSV Import Feature
This is a CSV import feature that allows you to import data from a CSV file into the database using Laravel and the csv league library.

## Library
The csv league library has been used for handling CSV file parsing and processing. It provides efficient and flexible functionality for working with CSV data.

## Usage
### Prerequisites
Before using this feature, make sure you have the following requirements fulfilled:

Laravel framework installed
The csv league library installed (you can install it via Composer)
run migration script by using `php artisan migrate` command
### Command
To import data from a CSV file, use the following command:

`php artisan import:csv {file} {--test}`
Replace {file} with the path to your CSV file.

The --test option is optional and can be used to run the import in test mode. Test mode will process the CSV file but will not save the data into the database.

Examples
Importing CSV file:

`php artisan import:csv storage/csv/stock.csv`
This command will import the data from the stock.csv file located in the storage/csv directory and save it into the database.

Importing CSV file in test mode:

`php artisan import:csv storage/csv/stock.csv --test`
This command will process the stock.csv file in test mode, which means the data will not be saved into the database.

## Testing
To run the unit tests for the CSV import feature, use the following command:


`php artisan test`
This will execute the test cases and ensure the functionality is working as expected.

Make sure to configure your database connection for testing in the Laravel configuration files.

Feel free to customize and adapt the code to fit your specific requirements and CSV file structure.

## Contributor
* Mehedi Hasan Munna, github: munna71bd22, email: munna71bd@gmail.com
