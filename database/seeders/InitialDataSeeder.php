<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Professor;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\ClassRoom;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create class
        $class = ClassRoom::create(['name' => 'E-Commerce']);

        // Create rooms
        $rooms = [
            ['name' => 'Salle 3', 'type' => 'Regular'],
            ['name' => 'Bibliothèque', 'type' => 'Library'],
            ['name' => 'Informatique', 'type' => 'Computer Lab'],
        ];
        foreach ($rooms as $room) {
            Room::create($room);
        }

        // Create professors
        $professors = [
            ['title' => 'M.', 'first_name' => 'EL BACHIRI', 'last_name' => 'Mouhcine'],
            ['title' => 'Mlle', 'first_name' => 'BOURZAMA', 'last_name' => 'Zakia'],
            ['title' => 'M.', 'first_name' => 'IDRISSI', 'last_name' => 'El Mehdi'],
            ['title' => 'M.', 'first_name' => 'Ali', 'last_name' => 'QAIDI'],
            ['title' => 'Mme', 'first_name' => 'HOUSNI', 'last_name' => 'Hafida'],
            ['title' => 'Mme', 'first_name' => 'BOUZIANE', 'last_name' => 'Mouna'],
            ['title' => 'M.', 'first_name' => 'Mouha', 'last_name' => 'ABARAOU'],
        ];
        foreach ($professors as $professor) {
            Professor::create($professor);
        }

        // Create subjects
        $subjects = [
            'Travaux de recherches et projet de formation',
            'Logiciels Commerciaux',
            'Anglais Technique',
            'Ateliers en Communication professionnelle',
            'Comptabilité Commerciale',
            'Entreprise et son Environnement',
            'Marketing',
            'Techniques de Négociation et de Ventes',
            'Initiation à l\'outil informatique',
            'Français & Techniques de Communication',
            'Gestion Commerciale',
            'Droit Commercial'
        ];
        foreach ($subjects as $subject) {
            Subject::create(['name' => $subject]);
        }

        // Create students
        $students = [
            ['first_name' => 'Hinde', 'last_name' => 'ABDELMAOUOJOUD'],
            ['first_name' => 'Firdaouss', 'last_name' => 'BAKADIR'],
            ['first_name' => 'Youssef', 'last_name' => 'BELMOKHTER'],
            ['first_name' => 'Zaynab', 'last_name' => 'CHARAF'],
            ['first_name' => 'Nadia', 'last_name' => 'DAMAAN'],
            ['first_name' => 'Fatima Zahrae', 'last_name' => 'EL KHADAR'],
            ['first_name' => 'Hamza', 'last_name' => 'EL OTHMANY'],
            ['first_name' => 'Mohammed', 'last_name' => 'HAFIDI'],
            ['first_name' => 'Wafae', 'last_name' => 'HICHAMI ALAOUI'],
            ['first_name' => 'Ghizlane', 'last_name' => 'HIZOUNE'],
            ['first_name' => 'Nohaila', 'last_name' => 'JOUHARI'],
            ['first_name' => 'Hiba', 'last_name' => 'KHATRI'],
            ['first_name' => 'Rania', 'last_name' => 'KHATTARI'],
            ['first_name' => 'Soukaina', 'last_name' => 'KHATTARI'],
            ['first_name' => 'Fatima Zahra', 'last_name' => 'KHISSI'],
            ['first_name' => 'Kenza', 'last_name' => 'KISSANI'],
            ['first_name' => 'Touda', 'last_name' => 'LAHBOUB'],
            ['first_name' => 'Imane', 'last_name' => 'NAIM'],
            ['first_name' => 'Yassine', 'last_name' => 'OUSSETTI'],
            ['first_name' => 'Imane', 'last_name' => 'RAOUD'],
            ['first_name' => 'Mohamed', 'last_name' => 'RHENICHE'],
            ['first_name' => 'Youssra', 'last_name' => 'SENNA'],
            ['first_name' => 'Halima', 'last_name' => 'TAOUSSI'],
        ];

        foreach ($students as $student) {
            $student['class_id'] = $class->id;
            Student::create($student);
        }

        // Create schedules
        $scheduleData = [
            [
                'day' => 'Lundi',
                'slots' => [
                    [
                        'subject' => 'Travaux de recherches et projet de formation',
                        'start_time' => '15:00',
                        'end_time' => '18:00',
                        'room' => 'Bibliothèque'
                    ]
                ]
            ],
            [
                'day' => 'Mardi',
                'slots' => [
                    [
                        'subject' => 'Logiciels Commerciaux',
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'professor' => 'EL BACHIRI Mouhcine',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Anglais Technique',
                        'start_time' => '15:00',
                        'end_time' => '18:00',
                        'professor' => 'BOURZAMA Zakia',
                        'room' => 'Salle 3'
                    ]
                ]
            ],
            [
                'day' => 'Mercredi',
                'slots' => [
                    [
                        'subject' => 'Ateliers en Communication professionnelle',
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'professor' => 'IDRISSI El Mehdi',
                        'room' => 'Salle 3',
                        'frequency' => 'Une fois/15jours'
                    ],
                    [
                        'subject' => 'Comptabilité Commerciale',
                        'start_time' => '14:00',
                        'end_time' => '16:00',
                        'professor' => 'Ali QAIDI',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Entreprise et son Environnement',
                        'start_time' => '16:00',
                        'end_time' => '18:00',
                        'professor' => 'Ali QAIDI',
                        'room' => 'Salle 3'
                    ]
                ]
            ],
            [
                'day' => 'Jeudi',
                'slots' => [
                    [
                        'subject' => 'Marketing',
                        'start_time' => '09:00',
                        'end_time' => '10:30',
                        'professor' => 'Ali QAIDI',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Techniques de Négociation et de Ventes',
                        'start_time' => '10:30',
                        'end_time' => '12:00',
                        'professor' => 'Ali QAIDI',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Initiation à l\'outil informatique',
                        'start_time' => '15:00',
                        'end_time' => '17:00',
                        'professor' => 'HOUSNI Hafida',
                        'room' => 'Informatique'
                    ]
                ]
            ],
            [
                'day' => 'Vendredi',
                'slots' => [
                    [
                        'subject' => 'Comptabilité Commerciale',
                        'start_time' => '09:00',
                        'end_time' => '12:00',
                        'professor' => 'Ali QAIDI',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Français & Techniques de Communication',
                        'start_time' => '15:00',
                        'end_time' => '17:00',
                        'professor' => 'BOUZIANE Mouna',
                        'room' => 'Salle 3'
                    ]
                ]
            ],
            [
                'day' => 'Samedi',
                'slots' => [
                    [
                        'subject' => 'Gestion Commerciale',
                        'start_time' => '09:00',
                        'end_time' => '13:00',
                        'professor' => 'Mouha ABARAOU',
                        'room' => 'Salle 3'
                    ],
                    [
                        'subject' => 'Droit Commercial',
                        'start_time' => '09:00',
                        'end_time' => '13:00',
                        'professor' => 'Mouha ABARAOU',
                        'room' => 'Salle 3'
                    ]
                ]
            ]
        ];

        foreach ($scheduleData as $dayData) {
            foreach ($dayData['slots'] as $slot) {
                $subject = Subject::where('name', $slot['subject'])->first();
                $room = Room::where('name', $slot['room'])->first();
                $professor = isset($slot['professor']) 
                    ? Professor::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $slot['professor'] . '%'])->first()
                    : null;

                Schedule::create([
                    'subject_id' => $subject->id,
                    'professor_id' => $professor?->id,
                    'room_id' => $room->id,
                    'class_id' => $class->id,
                    'day_of_week' => $dayData['day'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'frequency' => $slot['frequency'] ?? null
                ]);
            }
        }
    }
} 