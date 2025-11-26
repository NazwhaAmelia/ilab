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

        // Ensure photo is not set if upload fails
        if (! isset($data['photo']) || empty($data['photo'])) {
            unset($data['photo']);
        }

        if ($request->hasFile('photo')) {
            // Diagnostic logging to help debug Windows temp path issues
            try {
                \Log::debug('Upload diagnostics', [
                    'upload_tmp_dir' => ini_get('upload_tmp_dir'),
                    'sys_get_temp_dir' => sys_get_temp_dir(),
                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                    'post_max_size' => ini_get('post_max_size'),
                    'php_sapi' => PHP_SAPI,
                    'files_superglobal' => isset($_FILES['photo']) ? array_replace($_FILES['photo'], ['tmp_name' => $_FILES['photo']['tmp_name'] ?? null]) : null,
                ]);
            } catch (\Throwable $t) {
                \Log::warning('Failed to write upload diagnostics: '.$t->getMessage());
            }

            $file = $request->file('photo');
            if ($file && $file->isValid() && $file->getSize() > 0) {
                try {
                    // Guard: ensure uploaded file has a usable temp path before calling store()
                    $sourcePath = $file->getRealPath() ?: $file->getPathname() ?: null;
                    if (empty($sourcePath)) {
                        \Log::error('Uploaded file has no temp path (store)', [
                            'isValid' => $file->isValid(),
                            'size' => $file->getSize(),
                            'clientName' => $file->getClientOriginalName(),
                            'clientMime' => $file->getClientMimeType(),
                            'tmp' => $file->getRealPath(),
                            'pathname' => $file->getPathname() ?? null,
                        ]);

                        return redirect()->back()->withInput()->withErrors(['photo' => 'Gagal mengunggah foto: file upload tidak memiliki path sementara pada server. Coba lagi atau gunakan file lain.']);
                    }

                    // Use the real temp file path and write directly to storage to avoid
                    // passing the UploadedFile object into Storage which can cause
                    // fopen('', 'r') when the internal pathname is empty on Windows.
                    $sourcePath = $file->getRealPath() ?: $file->getPathname() ?: null;
                    $fileName = time().'_'.bin2hex(random_bytes(6)).'.'.$file->getClientOriginalExtension();
                    $relativePath = 'teachers/'.$fileName;

                    if ($sourcePath && is_file($sourcePath) && is_readable($sourcePath)) {
                        try {
                            $contents = @file_get_contents($sourcePath);
                            if ($contents !== false) {
                                Storage::disk('public')->put($relativePath, $contents);
                                $data['photo'] = $relativePath;
                                \Log::info('Photo uploaded via direct read/write', [
                                    'path' => $relativePath,
                                    'size' => $file->getSize(),
                                ]);
                            } else {
                                // Fallback to raw tmp move if reading failed
                                $tmpName = $_FILES['photo']['tmp_name'] ?? null;
                                if ($tmpName && is_uploaded_file($tmpName)) {
                                    $storagePath = storage_path('app/public/teachers');
                                    if (! is_dir($storagePath)) {
                                        mkdir($storagePath, 0755, true);
                                    }
                                    $destPath = $storagePath.DIRECTORY_SEPARATOR.$fileName;
                                    if (move_uploaded_file($tmpName, $destPath)) {
                                        $data['photo'] = $relativePath;
                                        \Log::info('Photo uploaded via tmp_name fallback (read failed)', ['dest' => $destPath]);
                                    } else {
                                        unset($data['photo']);
                                        \Log::warning('Fallback move_uploaded_file failed (read failed)', ['tmp' => $tmpName, 'dest' => $destPath]);
                                    }
                                } else {
                                    unset($data['photo']);
                                    \Log::warning('Could not read source file and no tmp_name available');
                                }
                            }
                        } catch (\Throwable $e) {
                            unset($data['photo']);
                            \Log::error('Direct file upload failed: '.$e->getMessage(), ['exception' => $e]);
                        }
                    } else {
                        // If source path isn't readable, try the previous tmp_name fallback
                        $tmpName = $_FILES['photo']['tmp_name'] ?? null;
                        if ($tmpName && is_uploaded_file($tmpName)) {
                            $storagePath = storage_path('app/public/teachers');
                            if (! is_dir($storagePath)) {
                                mkdir($storagePath, 0755, true);
                            }
                            $destPath = $storagePath.DIRECTORY_SEPARATOR.$fileName;
                            if (move_uploaded_file($tmpName, $destPath)) {
                                $data['photo'] = $relativePath;
                                \Log::info('Photo uploaded via tmp_name fallback (no readable source)', ['dest' => $destPath]);
                            } else {
                                unset($data['photo']);
                                \Log::warning('Fallback move_uploaded_file failed (no readable source)', ['tmp' => $tmpName, 'dest' => $destPath]);
                            }
                        } else {
                            unset($data['photo']);
                            \Log::warning('No readable source path and no tmp_name available');
                        }
                    }
                } catch (\Exception $e) {
                    unset($data['photo']);
                    \Log::error('Photo upload exception: '.$e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'exception' => $e,
                    ]);

                    return redirect()->back()->withInput()->withErrors(['photo' => 'Terjadi kesalahan saat mengunggah foto. Periksa log.']);
                }
            } else {
                unset($data['photo']);
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
                    // Hapus foto lama jika ada dan valid (bukan temp path)
                    if ($teacher->photo && ! str_contains($teacher->photo, 'php')) {
                        Storage::disk('public')->delete($teacher->photo);
                    }

                    // Guard: ensure uploaded file has a usable temp path before calling store()
                    $sourcePath = $file->getRealPath() ?: $file->getPathname() ?: null;
                    if (empty($sourcePath)) {
                        // Log full diagnostic and return with error to user
                        \Log::error('Uploaded file has no temp path', [
                            'isValid' => $file->isValid(),
                            'size' => $file->getSize(),
                            'clientName' => $file->getClientOriginalName(),
                            'clientMime' => $file->getClientMimeType(),
                            'tmp' => $file->getRealPath(),
                            'pathname' => $file->getPathname() ?? null,
                        ]);

                        return redirect()->back()->withInput()->withErrors(['photo' => 'Gagal mengunggah foto: file upload tidak memiliki path sementara pada server. Coba lagi atau gunakan file lain.']);
                    }

                    // Use direct read/write to storage to avoid passing the UploadedFile
                    // object into Storage which may cause fopen('', 'r') on Windows.
                    $sourcePath = $file->getRealPath() ?: $file->getPathname() ?: null;
                    $fileName = time().'_'.bin2hex(random_bytes(6)).'.'.$file->getClientOriginalExtension();
                    $relativePath = 'teachers/'.$fileName;

                    if ($sourcePath && is_file($sourcePath) && is_readable($sourcePath)) {
                        try {
                            $contents = @file_get_contents($sourcePath);
                            if ($contents !== false) {
                                Storage::disk('public')->put($relativePath, $contents);
                                $data['photo'] = $relativePath;
                                \Log::info('Photo updated via direct read/write', [
                                    'teacher_id' => $teacher->id,
                                    'path' => $relativePath,
                                    'size' => $file->getSize(),
                                ]);
                            } else {
                                unset($data['photo']);
                                \Log::warning('Could not read uploaded file for update');
                            }
                        } catch (\Throwable $e) {
                            unset($data['photo']);
                            \Log::error('Direct file update failed: '.$e->getMessage(), ['exception' => $e]);
                        }
                    } else {
                        unset($data['photo']);
                        \Log::warning('Uploaded file has no readable temp path for update', ['tmp' => $sourcePath]);
                    }
                } catch (\Exception $e) {
                    // Jangan ubah photo jika upload error
                    unset($data['photo']);
                    \Log::error('Photo update exception: '.$e->getMessage(), ['exception' => $e]);

                    return redirect()->back()->withInput()->withErrors(['photo' => 'Terjadi kesalahan saat menyimpan foto. Periksa log.']);
                }
            } else {
                // File tidak valid, jangan ubah photo
                unset($data['photo']);
            }
        } else {
            // No new photo uploaded, don't change existing photo
            unset($data['photo']);
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
