<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@elevone.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Teachers
        $teachers = [
            ['name' => 'Nazwha Amelia', 'email' => 'nazwha@elevone.com', 'phone' => '081234567890', 'bio' => 'Guru Astronomi'],
            ['name' => 'Budi Santoso', 'email' => 'budi@elevone.com', 'phone' => '081234567891', 'bio' => 'Guru Basis Data'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@elevone.com', 'phone' => '081234567892', 'bio' => 'Guru Pemrograman'],
            ['name' => 'Ahmad Fadli', 'email' => 'ahmad@elevone.com', 'phone' => '081234567893', 'bio' => 'Guru Bahasa Indonesia'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@elevone.com', 'phone' => '081234567894', 'bio' => 'Guru Matematika'],
        ];

        foreach ($teachers as $teacher) {
            Teacher::create($teacher);
        }

        // Create Classrooms
        $classrooms = [
            ['name' => 'Laboratorium 1 - RPL', 'code' => 'LAB-RPL-1', 'type' => 'lab', 'capacity' => 30, 'pic_id' => 1, 'description' => 'Lab RPL utama'],
            ['name' => 'Laboratorium 2 - Multimedia', 'code' => 'LAB-MM-2', 'type' => 'lab', 'capacity' => 25, 'pic_id' => 2, 'description' => 'Lab Multimedia'],
            ['name' => 'Ruang Kelas 12-1', 'code' => 'RK-12-1', 'type' => 'classroom', 'capacity' => 36, 'pic_id' => 3, 'description' => 'Ruang kelas reguler'],
            ['name' => 'Laboratorium 3 - Jaringan', 'code' => 'LAB-NET-3', 'type' => 'lab', 'capacity' => 28, 'pic_id' => 4, 'description' => 'Lab Jaringan Komputer'],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }

        // Create Subjects
        $subjects = [
            ['name' => 'Astronomi', 'code' => 'AST-101', 'description' => 'Ilmu tentang benda langit'],
            ['name' => 'Basis Data', 'code' => 'BD-201', 'description' => 'Database management'],
            ['name' => 'Pemrograman Berbasis Teks', 'code' => 'PBT-301', 'description' => 'Text-based programming'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIND-101', 'description' => 'Bahasa nasional'],
            ['name' => 'Matematika', 'code' => 'MTK-201', 'description' => 'Ilmu hitung'],
            ['name' => 'Pemrograman Web', 'code' => 'PWEB-401', 'description' => 'Web development'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Create Classes
        $classes = [
            ['name' => 'XII RPL 1', 'grade' => '12', 'major' => 'RPL', 'student_count' => 32, 'homeroom_teacher_id' => 1],
            ['name' => 'XII RPL 2', 'grade' => '12', 'major' => 'RPL', 'student_count' => 30, 'homeroom_teacher_id' => 2],
            ['name' => 'XII MM 1', 'grade' => '12', 'major' => 'Multimedia', 'student_count' => 28, 'homeroom_teacher_id' => 3],
        ];

        foreach ($classes as $class) {
            ClassModel::create($class);
        }

        // Create Schedules for Lab 2 - Multimedia (Senin)
        $schedules = [
            [
                'classroom_id' => 2, // Lab 2 - Multimedia
                'class_id' => 2, // XII RPL 2
                'subject_id' => 1, // Astronomi
                'teacher_id' => 1, // Nazwha Amelia
                'day' => 'monday',
                'start_time' => '07:30',
                'end_time' => '09:00',
                'notes' => 'Materi sistem tata surya',
            ],
            [
                'classroom_id' => 2,
                'class_id' => 2,
                'subject_id' => 2, // Basis Data
                'teacher_id' => 2,
                'day' => 'monday',
                'start_time' => '09:15',
                'end_time' => '12:00',
                'notes' => 'Praktikum SQL',
            ],
            [
                'classroom_id' => 2,
                'class_id' => 2,
                'subject_id' => 3, // Pemrograman
                'teacher_id' => 3,
                'day' => 'monday',
                'start_time' => '13:00',
                'end_time' => '14:00',
                'notes' => null,
            ],
            [
                'classroom_id' => 2,
                'class_id' => 2,
                'subject_id' => 4, // Bahasa Indonesia
                'teacher_id' => 4,
                'day' => 'monday',
                'start_time' => '14:00',
                'end_time' => '15:00',
                'notes' => null,
            ],
            // Tuesday
            [
                'classroom_id' => 2,
                'class_id' => 1, // XII RPL 1
                'subject_id' => 6, // Pemrograman Web
                'teacher_id' => 3,
                'day' => 'tuesday',
                'start_time' => '07:30',
                'end_time' => '10:00',
                'notes' => 'Laravel Framework',
            ],
            [
                'classroom_id' => 2,
                'class_id' => 3, // XII MM 1
                'subject_id' => 5, // Matematika
                'teacher_id' => 5,
                'day' => 'tuesday',
                'start_time' => '10:15',
                'end_time' => '12:00',
                'notes' => null,
            ],
            // Lab 1 schedules
            [
                'classroom_id' => 1, // Lab 1
                'class_id' => 1,
                'subject_id' => 2,
                'teacher_id' => 2,
                'day' => 'wednesday',
                'start_time' => '08:00',
                'end_time' => '10:30',
                'notes' => 'Database Design',
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
