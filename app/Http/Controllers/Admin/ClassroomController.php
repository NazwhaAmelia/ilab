<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassroomRequest;
use App\Http\Requests\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ClassroomController extends Controller
{
    public function index(): View
    {
        $classrooms = Classroom::with('pic')->latest()->paginate(10);

        return view('admin.classrooms.index', compact('classrooms'));
    }

    public function create(): View
    {
        $teachers = Teacher::all();

        return view('admin.classrooms.create', compact('teachers'));
    }

    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('pic_photo')) {
            $data['pic_photo'] = $request->file('pic_photo')->store('classrooms', 'public');
        }

        Classroom::create($data);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Ruang kelas/lab berhasil ditambahkan!');
    }

    public function edit(Classroom $classroom): View
    {
        $teachers = Teacher::all();

        return view('admin.classrooms.edit', compact('classroom', 'teachers'));
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('pic_photo')) {
            if ($classroom->pic_photo) {
                Storage::disk('public')->delete($classroom->pic_photo);
            }
            $data['pic_photo'] = $request->file('pic_photo')->store('classrooms', 'public');
        }

        $classroom->update($data);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Ruang kelas/lab berhasil diperbarui!');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        if ($classroom->pic_photo) {
            Storage::disk('public')->delete($classroom->pic_photo);
        }

        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Ruang kelas/lab berhasil dihapus!');
    }
}
