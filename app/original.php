<?php

class Importer
{
    /**
     * Import data from a specific CSV source

     * @param string $path path of file
     * @param string $delimiter of CSV file
     *
     * @return void
     * @throws UnableToProcessCsv
     */
    public static function import(string $path, string $delimiter = ","): void
    {
        try {
            // Open the CSV file for reading
            $csvFile = fopen($path, 'r');

            // if we do not have access to read the file we return
            if ($csvFile === false) {
                throw new UnableToProcessCsv("Unable to open CSV file: $path");
            }

            // Read and store the header row
            $header = fgetcsv($csvFile, null, $delimiter);

            // Initialize an empty array to store current data
            $data = [];

            // Process the CSV file in chunks
            while (!feof($csvFile)) {
                // Read row from the CSV file
                $row = fgetcsv($csvFile, null, $delimiter);
                if ($row !== false) {
                    // Skip processing if it's the header row
                    if ($row !== $header) {
                        // we save data as key => value pare
                        $data[] = array_combine($header, $row);
                    }
                }
            }

            // Close the CSV file
            fclose($csvFile);

            if (! empty($data)) {
                // Determine wwhere the data is comming from, based on URL:
                $parse = parse_url($data[0]['url']);

                $url = preg_replace('/^www\./i', '', $parse['host']);

                $url = str_replace('.', '_', $url);

                $value = ucwords(str_replace(['-', '_'], ' ', $url));

                // we create a method like: handleDomainName and we then call it dynamically
                $method = 'handle' .  str_replace(' ', '', $value);

                // Always check if method exists before calling
                if (method_exists(__CLASS__, $method) && is_callable([__CLASS__, $method], true, $callable_name)) {
                    // Process the chunk data (e.g., insert into database)
                    $callable_name($data);
                }
            }

            // Free memory by clearing the chunk data
            $data = [];
        } catch (Exception $e) {
            // Handle any exceptions thrown during CSV processing
            // Log the error or throw a custom exception
            echo $e->getMessage();
        }
    }

    /**
     * Handle boat-specs.com Import.
     *
     * @param array $records The records to process.
     *
     * @return void
     */
    protected static function handleBoatSpecsCom($records): void
    {
        foreach ($records as $key => $record) {
            // process each record and save into database
        }
    }

    /**
     * Handle boats.com Import.
     *
     * @param array $records The records to process.
     *
     * @return void
     */
    protected static function handleBoatsCom($records): void
    {
        foreach ($records as $key => $record) {
            // process each record and save into database
        }
    }
}

Importer::import('boats.csv'); // This will call the import() method
