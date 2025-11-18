<?php

namespace Database\Seeders;

use App\Models\ClassModel;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get necessary data
        $classroom = Classroom::first();
        $class = ClassModel::first();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if (! $classroom || ! $class || $teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->warn('Please run TeacherSeeder, ClassroomSeeder, ClassModelSeeder, and SubjectSeeder first!');

            return;
        }

        // Jadwal untuk hari ini (Tuesday)
        $schedules = [
            [
                'day' => 'tuesday',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'subject_id' => $subjects->where('code', 'MTK')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'tuesday',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'subject_id' => $subjects->where('code', 'PWK')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'tuesday',
                'start_time' => '10:15:00',
                'end_time' => '11:45:00',
                'subject_id' => $subjects->where('code', 'PBO')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'tuesday',
                'start_time' => '12:30:00',
                'end_time' => '14:00:00',
                'subject_id' => $subjects->where('code', 'BDG')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'tuesday',
                'start_time' => '14:00:00',
                'end_time' => '15:30:00',
                'subject_id' => $subjects->where('code', 'BING')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
        ];

        // Jadwal untuk hari lain (Monday)
        $mondaySchedules = [
            [
                'day' => 'monday',
                'start_time' => '07:00:00',
                'end_time' => '08:30:00',
                'subject_id' => $subjects->where('code', 'BIN')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'monday',
                'start_time' => '08:30:00',
                'end_time' => '10:00:00',
                'subject_id' => $subjects->where('code', 'FIS')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
            [
                'day' => 'monday',
                'start_time' => '10:15:00',
                'end_time' => '11:45:00',
                'subject_id' => $subjects->where('code', 'PKN')->first()->id,
                'teacher_id' => $teachers->random()->id,
                'classroom_id' => $classroom->id,
                'class_id' => $class->id,
            ],
        ];

        foreach (array_merge($schedules, $mondaySchedules) as $schedule) {
            Schedule::create($schedule);
        }
    }
}
