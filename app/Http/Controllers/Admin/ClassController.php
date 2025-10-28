<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ClassController extends Controller
{
    public function index(): View
    {
        $classes = ClassModel::with('homeroomTeacher')->latest()->paginate(10);

        return view('admin.classes.index', compact('classes'));
    }

    public function create(): View
    {
        $teachers = Teacher::all();

        return view('admin.classes.create', compact('teachers'));
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        ClassModel::create($request->validated());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(ClassModel $class): View
    {
        $teachers = Teacher::all();

        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    public function update(UpdateClassRequest $request, ClassModel $class): RedirectResponse
    {
        $class->update($request->validated());

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy(ClassModel $class): RedirectResponse
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
