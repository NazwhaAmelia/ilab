<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::latest()->paginate(10);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('admin.teachers.create');
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            // Pastikan file benar-benar valid dan ada
            if ($file && $file->isValid() && $file->getSize() > 0) {
                try {
                    // Buat folder jika belum ada
                    $storagePath = storage_path('app/public/teachers');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }
                    
                    // Generate nama file unik
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan menggunakan move atau storeAs
                    $path = $file->storeAs('teachers', $fileName, 'public');
                    
                    // Verifikasi file tersimpan
                    $fullPath = storage_path('app/public/' . $path);
                    if (file_exists($fullPath)) {
                        $data['photo'] = $path;
                        \Log::info('Photo uploaded successfully', [
                            'path' => $path,
                            'size' => filesize($fullPath)
                        ]);
                    } else {
                        \Log::error('Photo upload failed - file not found after save', [
                            'expected_path' => $fullPath
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Photo upload exception: ' . $e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'size' => $file->getSize()
                    ]);
                }
            } else {
                \Log::warning('Invalid photo file', [
                    'hasFile' => $request->hasFile('photo'),
                    'isValid' => $file ? $file->isValid() : false,
                    'size' => $file ? $file->getSize() : 0
                ]);
            }
        }

        Teacher::create($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru berhasil ditambahkan!');
    }

    public function show(Teacher $teacher): View
    {
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            if ($file && $file->isValid() && $file->getSize() > 0) {
                try {
                    // Hapus foto lama
                    if ($teacher->photo) {
                        Storage::disk('public')->delete($teacher->photo);
                    }
                    
                    // Buat folder jika belum ada
                    $storagePath = storage_path('app/public/teachers');
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }
                    
                    // Generate nama file unik
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    
                    // Simpan file
                    $path = $file->storeAs('teachers', $fileName, 'public');
                    
                    // Verifikasi file tersimpan
                    $fullPath = storage_path('app/public/' . $path);
                    if (file_exists($fullPath)) {
                        $data['photo'] = $path;
                        \Log::info('Photo updated successfully', [
                            'teacher_id' => $teacher->id,
                            'path' => $path,
                            'size' => filesize($fullPath)
                        ]);
                    } else {
                        \Log::error('Photo update failed - file not found after save');
                    }
                } catch (\Exception $e) {
                    \Log::error('Photo update exception: ' . $e->getMessage());
                }
            }
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru berhasil diperbarui!');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Guru berhasil dihapus!');
    }
}
