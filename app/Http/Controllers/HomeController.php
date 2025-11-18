<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index($classroomId = null)
    {
        // Mapping hari dalam bahasa Indonesia
        $dayMapping = [
            'Monday' => 'monday',
            'Tuesday' => 'tuesday',
            'Wednesday' => 'wednesday',
            'Thursday' => 'thursday',
            'Friday' => 'friday',
            'Saturday' => 'saturday',
            'Sunday' => 'sunday',
        ];

        $dayNames = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu',
        ];

        // Dapatkan hari ini
        $today = $dayMapping[Carbon::now()->format('l')];
        $todayName = $dayNames[$today];

        // Ambil semua ruangan untuk dropdown
        $classrooms = Classroom::with('pic')->orderBy('type')->orderBy('name')->get();

        // Tentukan ruangan yang dipilih (default: ruangan pertama)
        $selectedClassroom = null;
        if ($classroomId) {
            $selectedClassroom = Classroom::with('pic')->find($classroomId);
        }
        if (! $selectedClassroom && $classrooms->count() > 0) {
            $selectedClassroom = $classrooms->first();
        }

        // Ambil jadwal untuk ruangan yang dipilih hari ini
        $todaySchedules = collect();
        $currentSchedule = null;

        if ($selectedClassroom) {
            $todaySchedules = Schedule::with(['classroom', 'classModel', 'subject', 'teacher'])
                ->where('classroom_id', $selectedClassroom->id)
                ->where('day', $today)
                ->orderBy('start_time')
                ->get();

            // Cari jadwal yang sedang berlangsung
            $now = Carbon::now()->format('H:i:s');
            $currentSchedule = Schedule::with(['classroom', 'classModel', 'subject', 'teacher'])
                ->where('classroom_id', $selectedClassroom->id)
                ->where('day', $today)
                ->whereTime('start_time', '<=', $now)
                ->whereTime('end_time', '>', $now)
                ->first();
        }

        return view('home', compact('todaySchedules', 'currentSchedule', 'todayName', 'classrooms', 'selectedClassroom'));
    }

    public function welcome()
    {
        $schedules = Schedule::with(['classroom', 'classModel', 'subject', 'teacher'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        $classrooms = Classroom::orderBy('type')
            ->orderBy('name')
            ->get();

        $teachers = Teacher::take(6)->get();
        $subjects = Subject::all();
        $classes = ClassModel::with('homeroomTeacher')->get();

        $stats = [
            'total_teachers' => Teacher::count(),
            'total_classrooms' => Classroom::count(),
            'total_subjects' => Subject::count(),
            'total_classes' => ClassModel::count(),
        ];

        return view('welcome', compact('schedules', 'classrooms', 'teachers', 'subjects', 'classes', 'stats'));
    }
}
