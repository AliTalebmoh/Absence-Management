<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Professor;
use App\Models\Room;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\ClassRoom;
use App\Models\Analytics;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create class
        $class = ClassRoom::create(['name' => 'Commerce']);
        $class2 = ClassRoom::create(['name' => 'Infographie & Multimédia']);
        $class3 = ClassRoom::create(['name' => 'Informatique & Développement Web']);
        $class4 = ClassRoom::create(['name' => 'Energies Renouvelables']);
        $class5 = ClassRoom::create(['name' => 'Coupe & Couture / Tissage Horizontal']);
        $class6 = ClassRoom::create(['name' => 'Assistanat Social']);
        $class7 = ClassRoom::create(['name' => 'Coiffure & Esthétique']);

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

        // Create students for Infographie & Multimédia
        $students_multimedia = [
            ['first_name' => 'Amal', 'last_name' => 'ACHOUCH'],
            ['first_name' => 'Youssef', 'last_name' => 'BENKHADDA'],
            ['first_name' => 'Zakaria', 'last_name' => 'CHOUKA'],
            ['first_name' => 'Amine', 'last_name' => 'DRISSI-MELIANI'],
            ['first_name' => 'Hajar', 'last_name' => 'EL BOURAGI'],
            ['first_name' => 'Amine', 'last_name' => 'EL MARNISSI'],
            ['first_name' => 'Sanae', 'last_name' => 'HDIDOU'],
            ['first_name' => 'Karim', 'last_name' => 'KARAMA'],
            ['first_name' => 'Naima', 'last_name' => 'LAAROUSSI'],
            ['first_name' => 'Adnane', 'last_name' => 'LAATIK'],
            ['first_name' => 'Mohamed', 'last_name' => 'LAGHRISSI'],
            ['first_name' => 'Hamad', 'last_name' => 'SHEHAB'],
            ['first_name' => 'Hamza', 'last_name' => 'SOSSEY'],
            ['first_name' => 'Haijou', 'last_name' => 'ZAAOUAOUI']
        ];

        foreach ($students_multimedia as $student) {
            $student['class_id'] = $class2->id;
            Student::create($student);
        }

        // Create students for Informatique & Développement Web
        $students_dev = [
            ['first_name' => 'Houssam', 'last_name' => 'AL HYANE'],
            ['first_name' => 'Fatima', 'last_name' => 'BELLA'],
            ['first_name' => 'Salah-Eddine', 'last_name' => 'BOURRAY'],
            ['first_name' => 'Rachida', 'last_name' => 'EL AKHFACH'],
            ['first_name' => 'Abdelmunaim', 'last_name' => 'EL HAANANI'],
            ['first_name' => 'Aymane', 'last_name' => 'EL MOUSSAOUI'],
            ['first_name' => 'Yassir', 'last_name' => 'ESSABBAHY'],
            ['first_name' => 'Meryem', 'last_name' => 'HASNAOUI'],
            ['first_name' => 'Fatima-Ezzahrae', 'last_name' => 'HIDA'],
            ['first_name' => 'Mohamed', 'last_name' => 'HMICHANE'],
            ['first_name' => 'Asmae', 'last_name' => 'LAMGHARI'],
            ['first_name' => 'Sanae', 'last_name' => 'LEGNAFDI'],
            ['first_name' => 'Hasnae', 'last_name' => 'MOUHADANE'],
            ['first_name' => 'Mohamed Amine', 'last_name' => 'OMARI'],
            ['first_name' => 'OTMANE', 'last_name' => 'SAAID'],
            ['first_name' => 'Youness', 'last_name' => 'SEHLI'],
            ['first_name' => 'Smail', 'last_name' => 'YAZIDI']
        ];

        foreach ($students_dev as $student) {
            $student['class_id'] = $class3->id;
            Student::create($student);
        }

        // Create students for Energies Renouvelables
        $students_energies = [
            ['first_name' => 'Rida', 'last_name' => 'AAJOUL'],
            ['first_name' => 'Redwane', 'last_name' => 'AIT M\'HAMED'],
            ['first_name' => 'Said', 'last_name' => 'AMQOR'],
            ['first_name' => 'Adil', 'last_name' => 'AZEROIL'],
            ['first_name' => 'Fadwa', 'last_name' => 'AZZOU'],
            ['first_name' => 'Sanae', 'last_name' => 'AZZOU'],
            ['first_name' => 'Ismail', 'last_name' => 'BOUHAFRA'],
            ['first_name' => 'Ibrahim', 'last_name' => 'CHAIBI'],
            ['first_name' => 'Amine', 'last_name' => 'DERWICH'],
            ['first_name' => 'Mehdi', 'last_name' => 'ECHOUYKH'],
            ['first_name' => 'Mohcine', 'last_name' => 'EL OUAZYRY'],
            ['first_name' => 'Anouar', 'last_name' => 'FAWZI'],
            ['first_name' => 'Yassine', 'last_name' => 'FEDAILI'],
            ['first_name' => 'Faouad', 'last_name' => 'GUEROUAT'],
            ['first_name' => 'Salaheddine', 'last_name' => 'HASSANI'],
            ['first_name' => 'Youssef', 'last_name' => 'KELLA'],
            ['first_name' => 'Zakariae', 'last_name' => 'LAMRAOUI'],
            ['first_name' => 'Ali', 'last_name' => 'LAZAR'],
            ['first_name' => 'Mohamed Amine', 'last_name' => 'MOUILAOUI'],
            ['first_name' => 'Mohamed', 'last_name' => 'OUAMRAN'],
            ['first_name' => 'Yassine', 'last_name' => 'OUBRAHIM'],
            ['first_name' => 'Saad', 'last_name' => 'OUSGHAB'],
            ['first_name' => 'Ayman', 'last_name' => 'OUTALEB'],
            ['first_name' => 'Mohamed', 'last_name' => 'SAMLI'],
            ['first_name' => 'Achraf', 'last_name' => 'SAYDI']
        ];

        foreach ($students_energies as $student) {
            $student['class_id'] = $class4->id;
            Student::create($student);
        }

        // Create students for Coupe & Couture / Tissage Horizontal
        $students_couture = [
            ['first_name' => 'Mina', 'last_name' => 'ACHBAB'],
            ['first_name' => 'Nihad', 'last_name' => 'AMIRI'],
            ['first_name' => 'Aicha', 'last_name' => 'ANEJDAME'],
            ['first_name' => 'Khadija', 'last_name' => 'BARBACH'],
            ['first_name' => 'Meryem', 'last_name' => 'BROUROU'],
            ['first_name' => 'Laila', 'last_name' => 'CHABA'],
            ['first_name' => 'Meryem', 'last_name' => 'CHADLI'],
            ['first_name' => 'Khadija', 'last_name' => 'CHAMKH'],
            ['first_name' => 'Najia', 'last_name' => 'EL ANSARI'],
            ['first_name' => 'Meryam', 'last_name' => 'EL ASSBOUNI'],
            ['first_name' => 'Khadija', 'last_name' => 'EL KAMEL'],
            ['first_name' => 'Aya', 'last_name' => 'EZZAHIR'],
            ['first_name' => 'Soukaina', 'last_name' => 'JAOUHARI'],
            ['first_name' => 'Nadia', 'last_name' => 'KADOUSSI'],
            ['first_name' => 'Nadia', 'last_name' => 'NOUALI'],
            ['first_name' => 'Sanae', 'last_name' => 'OUBERRI'],
            ['first_name' => 'Sanae', 'last_name' => 'OUKHCHINE'],
            ['first_name' => 'Fatima Ez Zahra', 'last_name' => 'REGRAGUI'],
            ['first_name' => 'Hajar', 'last_name' => 'SEHLI'],
            ['first_name' => 'Fatima', 'last_name' => 'TOUMI']
        ];

        foreach ($students_couture as $student) {
            $student['class_id'] = $class5->id;
            Student::create($student);
        }

        // Create students for Assistanat Social
        $students_social = [
            ['first_name' => 'Ikrame', 'last_name' => 'AIT LMADANI'],
            ['first_name' => 'Fatima Zohra', 'last_name' => 'AKHOUTIL'],
            ['first_name' => 'Sanae', 'last_name' => 'AKKEBAR'],
            ['first_name' => 'Hajjou', 'last_name' => 'BAASSINE'],
            ['first_name' => 'Nouhaila', 'last_name' => 'BACHIRI'],
            ['first_name' => 'Houda', 'last_name' => 'BERZLINE'],
            ['first_name' => 'Zineb', 'last_name' => 'BOUFALA'],
            ['first_name' => 'Hajiba', 'last_name' => 'BOURAGAA'],
            ['first_name' => 'Ouissal', 'last_name' => 'DAMANI'],
            ['first_name' => 'Assia', 'last_name' => 'EL ABDELLAOUI'],
            ['first_name' => 'Bouchra', 'last_name' => 'EL BOUAZZAOUI'],
            ['first_name' => 'Imane', 'last_name' => 'EL BOUZKRAOUI EL ALAOUI'],
            ['first_name' => 'Nawal', 'last_name' => 'EL-ABBADI'],
            ['first_name' => 'Chaima', 'last_name' => 'ELHADIOUI'],
            ['first_name' => 'Nadia', 'last_name' => 'HADDOUCH'],
            ['first_name' => 'Houria', 'last_name' => 'IBRAHIMI'],
            ['first_name' => 'Fatima', 'last_name' => 'IRHOUD'],
            ['first_name' => 'Ilyas', 'last_name' => 'JABOUR'],
            ['first_name' => 'Charif', 'last_name' => 'LOUHIDI'],
            ['first_name' => 'Chaimae', 'last_name' => 'MOUFADDAL']
        ];

        foreach ($students_social as $student) {
            $student['class_id'] = $class6->id;
            Student::create($student);
        }

        // Create students for Coiffure & Esthétique
        $students_coiffure = [
            ['first_name' => 'Souad', 'last_name' => 'AIT M\'HAMED'],
            ['first_name' => 'Salwa', 'last_name' => 'AZOUGAGH'],
            ['first_name' => 'Hanane', 'last_name' => 'BOULBABE'],
            ['first_name' => 'Khaoula', 'last_name' => 'BOURASS'],
            ['first_name' => 'Saida', 'last_name' => 'BOURHAIBA'],
            ['first_name' => 'Fatima', 'last_name' => 'BOUYACQUB'],
            ['first_name' => 'Nouahaila', 'last_name' => 'BOUZIANE'],
            ['first_name' => 'Hanane', 'last_name' => 'EL BADAOUI'],
            ['first_name' => 'Hassna', 'last_name' => 'EL BADAOUI'],
            ['first_name' => 'Mouna', 'last_name' => 'EL GHAZI'],
            ['first_name' => 'Farida', 'last_name' => 'EL KHOUYANI'],
            ['first_name' => 'Dounia', 'last_name' => 'EL MEKAOUI'],
            ['first_name' => 'Nisrine', 'last_name' => 'FADILI'],
            ['first_name' => 'Asmae', 'last_name' => 'HAJJI'],
            ['first_name' => 'Hiba', 'last_name' => 'IKKOU'],
            ['first_name' => 'Meriem', 'last_name' => 'KERROUMI'],
            ['first_name' => 'Hajar', 'last_name' => 'KHALESS'],
            ['first_name' => 'Khaoula', 'last_name' => 'LAHLIMI'],
            ['first_name' => 'Aya', 'last_name' => 'LAKDIM'],
            ['first_name' => 'Bouchra', 'last_name' => 'LAQTIB'],
            ['first_name' => 'Mounia', 'last_name' => 'OUBEJJA'],
            ['first_name' => 'Ilham', 'last_name' => 'OUHAMMADI'],
            ['first_name' => 'Latifa', 'last_name' => 'RAZZOUK'],
            ['first_name' => 'Aya', 'last_name' => 'SANAA'],
            ['first_name' => 'Nouhaila', 'last_name' => 'TAHIRI']
        ];

        foreach ($students_coiffure as $student) {
            $student['class_id'] = $class7->id;
            Student::create($student);
        }

        // Create initial analytics for each class
        $classes = [$class, $class2, $class3, $class4, $class5, $class6, $class7];
        foreach ($classes as $class_item) {
            $total_students = Student::where('class_id', $class_item->id)->count();
            Analytics::create([
                'class_id' => $class_item->id,
                'total_students' => $total_students,
                'total_absences' => 0,
                'total_present' => 0,
                'attendance_rate' => 100,
                'average_performance' => 0,
                'monthly_attendance' => json_encode([
                    'January' => 0,
                    'February' => 0,
                    'March' => 0,
                    'April' => 0,
                    'May' => 0,
                    'June' => 0,
                    'July' => 0,
                    'August' => 0,
                    'September' => 0,
                    'October' => 0,
                    'November' => 0,
                    'December' => 0
                ]),
                'subject_performance' => json_encode([])
            ]);
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