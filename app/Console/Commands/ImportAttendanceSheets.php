<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\Absence;
use App\Models\ClassRoom;
use Carbon\Carbon;

class ImportAttendanceSheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:attendance-sheets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import absences from the attendance sheets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting import of attendance sheets...');

        // Get the Commerce class
        $class = ClassRoom::where('name', 'Commerce')->first();
        if (!$class) {
            $this->error('Commerce class not found!');
            return 1;
        }

        $processedCount = 0;
        $skippedCount = 0;

        // Load attendance data
        $attendanceData = require database_path('attendance_data.php');

        foreach ($attendanceData as $weekData) {
            $this->info("\nProcessing week: {$weekData['week']}");
            
            // Parse the start date from the week range
            $startDate = Carbon::createFromFormat('d/m/Y', explode(' to ', $weekData['week'])[0]);
            
            foreach ($weekData['absences'] as $studentName => $dayAbsences) {
                // Split student name into first and last name
                $nameParts = explode(' ', $studentName, 2);
                $lastName = $nameParts[0];
                $firstName = $nameParts[1] ?? '';

                // Find the student
                $student = Student::where('class_id', $class->id)
                    ->where('first_name', $firstName)
                    ->where('last_name', $lastName)
                    ->first();

                if (!$student) {
                    $this->warn("Student not found: $studentName");
                    $skippedCount++;
                    continue;
                }

                foreach ($dayAbsences as $day => $periods) {
                    $date = clone $startDate;
                    switch ($day) {
                        case 'Monday': $date->startOfWeek(); break;
                        case 'Tuesday': $date->startOfWeek()->addDay(); break;
                        case 'Wednesday': $date->startOfWeek()->addDays(2); break;
                        case 'Thursday': $date->startOfWeek()->addDays(3); break;
                        case 'Friday': $date->startOfWeek()->addDays(4); break;
                        case 'Saturday': $date->startOfWeek()->addDays(5); break;
                    }

                    foreach ($periods as $period => $status) {
                        if ($status === 'A') {
                            // Create or update absence record
                            Absence::updateOrCreate([
                                'student_id' => $student->id,
                                'date' => $date->format('Y-m-d'),
                                'period' => $period
                            ], [
                                'hours_absent' => $period === 'morning' ? 3 : 4
                            ]);
                            $processedCount++;
                        }
                    }
                }
            }
        }

        $this->info("\nImport completed!");
        $this->info("Processed absences: $processedCount");
        $this->info("Skipped records: $skippedCount");

        return 0;
    }

    private function parseAttendanceSheet($content)
    {
        // This method will be implemented to parse the attendance sheet data
        // and populate the $attendanceData array
    }
}
