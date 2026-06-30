<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class JsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the path to your JSON files
        $directoryPath = database_path('json_seeds');

        // Check if the directory exists
        if (! File::exists($directoryPath)) {
            $this->command->error("Directory not found at: {$directoryPath}");

            return;
        }

        // Get all files from the directory
        $files = File::files($directoryPath);

        foreach ($files as $file) {
            // Process only JSON files
            if ($file->getExtension() !== 'json') {
                continue;
            }

            // Get the filename without extension to use as the table name
            $tableName = $file->getFilenameWithoutExtension();

            // Skip if the table does not exist in the database
            if (! Schema::hasTable($tableName)) {
                $this->command->warn("Skipping file: '{$file->getFilename()}'. Table '{$tableName}' does not exist.");

                continue;
            }

            // Read and decode JSON content
            $jsonContent = File::get($file->getRealPath());
            $data = json_decode($jsonContent, true);

            // Skip if JSON is empty or invalid
            if (empty($data)) {
                $this->command->warn("Skipping table '{$tableName}': JSON file is empty or invalid.");

                continue;
            }

            // FIX: If data is wrapped in an outer key matching the table name, unwrap it
            if (isset($data[$tableName])) {
                $data = $data[$tableName];
            }

            $this->command->info("Seeding table: '{$tableName}'...");

            // Double-check if the unwrapped array is a single record or multiple records
            if (array_keys($data) !== range(0, count($data) - 1)) {
                $data = [$data];
            }

            // Insert data into the table safely
            foreach ($data as &$row) {
                foreach ($row as $key => $value) {
                    if (is_array($value)) {
                        $row[$key] = json_encode(
                            $value,
                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                        );
                    }
                }
            }

            DB::table($tableName)->insert($data);
        }

        $this->command->info('JSON directory seeding completed successfully!');
    }
}
