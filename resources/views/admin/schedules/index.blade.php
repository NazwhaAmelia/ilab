@extends('layouts.admin')

@section('title', 'Jadwal')
@section('page-title', 'Manajemen Jadwal')

@section('header-actions')
<a href="{{ route('admin.schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
    <i class="fas fa-plus mr-2"></i>Tambah Jadwal
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4">
        <i class="fas fa-filter text-blue-500 mr-2"></i>
        Filter Berdasarkan Ruangan
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="md:col-span-3">
            <div class="flex gap-2">
                <select name="classroom_id" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Semua Ruangan --</option>
                    @foreach($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ $selectedClassroom == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }} ({{ ucfirst($classroom->type) }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                @if($selectedClassroom)
                    <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Reset
                    </a>
                @endif
            </div>
        </form>
        
        @if($selectedClassroom)
            <a href="{{ route('admin.schedules.classroom', $selectedClassroom) }}" 
               target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition text-center">
                <i class="fas fa-external-link-alt mr-2"></i>Lihat Fullscreen
            </a>
        @endif
    </div>
</div>

@if($schedules->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        @php
            $days = [
                'monday' => 'Senin',
                'tuesday' => 'Selasa',
                'wednesday' => 'Rabu',
                'thursday' => 'Kamis',
                'friday' => 'Jumat',
                'saturday' => 'Sabtu',
                'sunday' => 'Minggu'
            ];
        @endphp

        @foreach($days as $dayKey => $dayName)
            @if(isset($schedules[$dayKey]) && $schedules[$dayKey]->count() > 0)
                <div class="border-b border-gray-200 last:border-b-0">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3">
                        <h3 class="text-white font-bold text-lg">
                            <i class="fas fa-calendar-day mr-2"></i>{{ $dayName }}
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($schedules[$dayKey] as $schedule)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 hover:shadow-lg transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-door-open text-blue-500 mr-2"></i>
                                                <span class="font-semibold text-gray-800">{{ $schedule->classroom->name }}</span>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-users text-purple-500 mr-2"></i>
                                                <span class="text-gray-700">{{ $schedule->classModel->name }}</span>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-book text-green-500 mr-2"></i>
                                                <span class="text-gray-700 font-medium">{{ $schedule->subject->name }}</span>
                                            </div>
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-chalkboard-teacher text-orange-500 mr-2"></i>
                                                <span class="text-gray-700">{{ $schedule->teacher->name }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock text-red-500 mr-2"></i>
                                                <span class="text-gray-600 text-sm font-mono">
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                            @if($schedule->notes)
                                                <div class="mt-2 text-sm text-gray-500 italic">
                                                    <i class="fas fa-sticky-note mr-1"></i>{{ $schedule->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-3 pt-3 border-t border-gray-300">
                                        <a href="{{ route('admin.schedules.edit', $schedule) }}" 
                                           class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 rounded-lg transition text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" 
                                              method="POST" 
                                              class="flex-1"
                                              onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition text-sm">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@else
    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-calendar-times text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Jadwal</h3>
        <p class="text-gray-500 mb-6">
            @if($selectedClassroom)
                Belum ada jadwal untuk ruangan yang dipilih
            @else
                Mulai tambahkan jadwal untuk mengelola waktu pembelajaran
            @endif
        </p>
        <a href="{{ route('admin.schedules.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Jadwal Baru
        </a>
    </div>
@endif
@endsection
