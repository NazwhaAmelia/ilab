@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Stats Cards --}}
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Total Guru</p>
                <h3 class="text-4xl font-bold mt-2">{{ $stats['teachers'] }}</h3>
            </div>
            <div class="bg-white bg-opacity-30 rounded-full p-4">
                <i class="fas fa-chalkboard-teacher text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Ruang Kelas/Lab</p>
                <h3 class="text-4xl font-bold mt-2">{{ $stats['classrooms'] }}</h3>
            </div>
            <div class="bg-white bg-opacity-30 rounded-full p-4">
                <i class="fas fa-door-open text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Total Kelas</p>
                <h3 class="text-4xl font-bold mt-2">{{ $stats['classes'] }}</h3>
            </div>
            <div class="bg-white bg-opacity-30 rounded-full p-4">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Total Jadwal</p>
                <h3 class="text-4xl font-bold mt-2">{{ $stats['schedules'] }}</h3>
            </div>
            <div class="bg-white bg-opacity-30 rounded-full p-4">
                <i class="fas fa-calendar-alt text-3xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
        Aksi Cepat
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.schedules.create') }}" class="bg-blue-50 hover:bg-blue-100 rounded-xl p-4 flex items-center transition">
            <div class="bg-blue-500 rounded-lg p-3 mr-4">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Tambah Jadwal</p>
                <p class="text-sm text-gray-500">Buat jadwal baru</p>
            </div>
        </a>

        <a href="{{ route('admin.teachers.create') }}" class="bg-green-50 hover:bg-green-100 rounded-xl p-4 flex items-center transition">
            <div class="bg-green-500 rounded-lg p-3 mr-4">
                <i class="fas fa-user-plus text-white text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Tambah Guru</p>
                <p class="text-sm text-gray-500">Daftarkan guru baru</p>
            </div>
        </a>

        <a href="{{ route('admin.classrooms.create') }}" class="bg-purple-50 hover:bg-purple-100 rounded-xl p-4 flex items-center transition">
            <div class="bg-purple-500 rounded-lg p-3 mr-4">
                <i class="fas fa-door-open text-white text-xl"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800">Tambah Ruangan</p>
                <p class="text-sm text-gray-500">Daftarkan ruangan baru</p>
            </div>
        </a>
    </div>
</div>

{{-- Recent Schedules --}}
<div class="bg-white rounded-2xl shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-history text-blue-500 mr-2"></i>
        Jadwal Terbaru
    </h3>

    @if($recentSchedules->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Ruangan</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Mata Pelajaran</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Guru</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Hari</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentSchedules as $schedule)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-door-open mr-1"></i>
                                    {{ $schedule->classroom->name }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-700">{{ $schedule->classModel->name }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $schedule->subject->name }}</td>
                            <td class="py-3 px-4 text-gray-700">{{ $schedule->teacher->name }}</td>
                            <td class="py-3 px-4">
                                <span class="capitalize text-gray-600">{{ ucfirst($schedule->day) }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('admin.schedules.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua Jadwal <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @else
        <div class="text-center py-12 text-gray-500">
            <i class="fas fa-calendar-times text-5xl mb-3"></i>
            <p>Belum ada jadwal yang dibuat</p>
            <a href="{{ route('admin.schedules.create') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i>Buat Jadwal Pertama
            </a>
        </div>
    @endif
</div>
@endsection
