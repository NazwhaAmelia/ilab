@extends('layouts.admin')

@section('title', 'Guru')
@section('page-title', 'Manajemen Guru')

@section('header-actions')
<a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
    <i class="fas fa-plus mr-2"></i>Tambah Guru
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    @if($teachers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bio</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($teachers as $teacher)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                @if($teacher->photo)
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" 
                                         alt="{{ $teacher->name }}" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-500">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr($teacher->name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $teacher->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $teacher->email }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $teacher->phone ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <p class="truncate max-w-xs">{{ $teacher->bio ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.teachers.edit', $teacher) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t">
            {{ $teachers->links() }}
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-user-slash text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Guru</h3>
            <p class="mb-6">Mulai tambahkan data guru</p>
            <a href="{{ route('admin.teachers.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Guru Pertama
            </a>
        </div>
    @endif
</div>
@endsection
