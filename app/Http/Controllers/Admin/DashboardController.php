<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'teachers' => Teacher::count(),
            'classrooms' => Classroom::count(),
            'classes' => ClassModel::count(),
            'subjects' => Subject::count(),
            'schedules' => Schedule::count(),
        ];

        $recentSchedules = Schedule::with(['classroom', 'classModel', 'subject', 'teacher'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentSchedules'));
    }
}
