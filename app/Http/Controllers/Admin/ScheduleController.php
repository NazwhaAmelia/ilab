<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\ClassModel;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $classrooms = Classroom::all();
        $selectedClassroom = $request->classroom_id;

        $schedules = Schedule::with(['classroom', 'classModel', 'subject', 'teacher'])
            ->when($selectedClassroom, fn ($q) => $q->where('classroom_id', $selectedClassroom))
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('admin.schedules.index', compact('schedules', 'classrooms', 'selectedClassroom'));
    }

    public function create(): View
    {
        $classrooms = Classroom::all();
        $classes = ClassModel::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('admin.schedules.create', compact('classrooms', 'classes', 'subjects', 'teachers'));
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        Schedule::create($request->validated());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function edit(Schedule $schedule): View
    {
        $classrooms = Classroom::all();
        $classes = ClassModel::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();

        return view('admin.schedules.edit', compact('schedule', 'classrooms', 'classes', 'subjects', 'teachers'));
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        $schedule->update($request->validated());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }

    public function byClassroom(Classroom $classroom): View
    {
        $schedules = Schedule::with(['classModel', 'subject', 'teacher'])
            ->where('classroom_id', $classroom->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return view('admin.schedules.by-classroom', compact('classroom', 'schedules'));
    }
}
