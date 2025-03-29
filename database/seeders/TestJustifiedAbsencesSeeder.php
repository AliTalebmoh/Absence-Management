<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absence;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestJustifiedAbsencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if we have absences in the database
        $absenceCount = Absence::count();
        echo "Current absence count: $absenceCount\n";
        
        // If no absences, create some
        if ($absenceCount < 10) {
            echo "Creating test absences...\n";
            
            $students = Student::limit(10)->get();
            if ($students->isEmpty()) {
                echo "No students found. Please seed the students table first.\n";
                return;
            }
            
            $dates = [
                Carbon::now()->subDays(1),
                Carbon::now()->subDays(2),
                Carbon::now()->subDays(3),
                Carbon::now()->subWeek(),
                Carbon::now(),
            ];
            
            foreach ($students as $student) {
                foreach ($dates as $date) {
                    Absence::create([
                        'student_id' => $student->id,
                        'date' => $date,
                        'period' => rand(0, 1) ? 'morning' : 'afternoon',
                        'hours_absent' => rand(0, 1) ? 4 : 3,
                        'justified' => false
                    ]);
                }
            }
            
            echo "Created " . (count($students) * count($dates)) . " test absences\n";
        }
        
        // Mark 5 random absences as justified
        $absencesToUpdate = Absence::where('justified', false)->inRandomOrder()->limit(5)->get();
        
        if ($absencesToUpdate->isEmpty()) {
            echo "No absences found to mark as justified.\n";
            return;
        }
        
        foreach ($absencesToUpdate as $absence) {
            $absence->justified = true;
            $absence->save();
            
            echo "Marked absence ID {$absence->id} for student {$absence->student_id} as justified\n";
        }
        
        echo "Total justified absences: " . Absence::where('justified', true)->count() . "\n";
        
        // Update analytics for all classes
        $classes = ClassRoom::all();
        
        foreach ($classes as $class) {
            // Count unjustified absences
            $unjustifiedAbsences = Absence::whereHas('student', function($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->where('justified', false)
            ->count();
            
            // Count justified absences
            $justifiedAbsences = Absence::whereHas('student', function($query) use ($class) {
                $query->where('class_id', $class->id);
            })
            ->where('justified', true)
            ->count();
            
            // Create or update analytics
            DB::table('analytics')
                ->updateOrInsert(
                    ['class_id' => $class->id],
                    [
                        'total_absences' => $unjustifiedAbsences,
                        'total_justified_absences' => $justifiedAbsences,
                        'total_students' => Student::where('class_id', $class->id)->count(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]
                );
                
            echo "Updated analytics for class {$class->name}: {$unjustifiedAbsences} unjustified, {$justifiedAbsences} justified\n";
        }
    }
}
