<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create default classes
        $classes = [];
        for ($i = 1; $i <= 3; $i++) {
            $classes[] = ClassRoom::create([
                'name' => "Class $i"
            ]);
        }

        // Create default subjects
        for ($i = 1; $i <= 5; $i++) {
            Subject::create([
                'name' => "Subject $i"
            ]);
        }

        // Create default students (5 students per class)
        foreach ($classes as $class) {
            for ($i = 1; $i <= 5; $i++) {
                Student::create([
                    'name' => "Student {$class->id}-$i",
                    'class_id' => $class->id
                ]);
            }
        }
    }
} 