<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Absence;
use App\Models\ClassRoom;
use Carbon\Carbon;

class ImportAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:absences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import absences from the JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting import of absences...');

        // Get the Commerce class
        $class = ClassRoom::where('name', 'Commerce')->first();
        if (!$class) {
            $this->error('Commerce class not found!');
            return 1;
        }

        // Read the JSON file
        $jsonPath = database_path('absence.json');
        if (!file_exists($jsonPath)) {
            $this->error('absence.json file not found!');
            return 1;
        }

        $absences = json_decode(file_get_contents($jsonPath), true);
        $bar = $this->output->createProgressBar(count($absences));
        $bar->start();

        $processed = 0;
        $skipped = 0;

        foreach ($absences as $absenceData) {
            // Skip if not absent
            if ($absenceData['status'] !== 'A') {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Get student name parts
            $nameParts = explode(' ', $absenceData['student_name'], 2);
            $lastName = $nameParts[0];
            $firstName = $nameParts[1] ?? '';

            // Find the student
            $student = Student::where('class_id', $class->id)
                ->where('first_name', $firstName)
                ->where('last_name', $lastName)
                ->first();

            if (!$student) {
                $this->warn("\nStudent not found: " . $absenceData['student_name']);
                $bar->advance();
                continue;
            }

            // Convert date format
            $date = Carbon::createFromFormat('d/m/Y', $absenceData['date'])->format('Y-m-d');

            // Determine period and hours
            $period = $absenceData['morning_or_afternoon'] === 'Matin' ? 'morning' : 'afternoon';
            $hours = $period === 'morning' ? 3 : 4;

            // Create the absence record
            Absence::updateOrCreate([
                'student_id' => $student->id,
                'date' => $date,
                'period' => $period,
            ], [
                'hours_absent' => $hours
            ]);

            $processed++;
            $bar->advance();
        }

        $bar->finish();

        $this->newLine();
        $this->info("Import completed!");
        $this->info("Processed absences: $processed");
        $this->info("Skipped records (not absent): $skipped");

        return 0;
    }
}
