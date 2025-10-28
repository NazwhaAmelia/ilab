<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal {{ $classroom->name }} - ElevOne-lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (min-width: 768px) {
            .fullscreen-container {
                height: 100vh;
                width: 100vw;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            
            .schedule-content {
                flex: 1;
                overflow-y: auto;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="fullscreen-container">
        {{-- Header --}}
        <header class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-xl flex-shrink-0">
            <div class="container mx-auto px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-full">
                            <i class="fas fa-door-open text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold">{{ $classroom->name }}</h1>
                            <p class="text-blue-100">
                                <i class="fas fa-tag mr-2"></i>
                                {{ ucfirst($classroom->type) }} 
                                @if($classroom->capacity)
                                    | Kapasitas: {{ $classroom->capacity }} orang
                                @endif
                            </p>
                            @if($classroom->pic)
                                <p class="text-blue-100 text-sm mt-1">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    PIC: {{ $classroom->pic->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div id="current-time" class="text-4xl font-mono font-bold mb-1"></div>
                        <div id="current-date" class="text-lg text-blue-100"></div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Schedule Content --}}
        <main class="schedule-content p-8">
            @if($schedules->count() > 0)
                <div class="grid gap-6">
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
                        $currentDay = strtolower(now()->format('l'));
                    @endphp

                    @foreach($days as $dayKey => $dayName)
                        @if(isset($schedules[$dayKey]) && $schedules[$dayKey]->count() > 0)
                            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform hover:scale-[1.02] transition duration-300 {{ $dayKey === $currentDay ? 'ring-4 ring-blue-500' : '' }}">
                                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 flex items-center justify-between">
                                    <h2 class="text-white font-bold text-2xl flex items-center">
                                        <i class="fas fa-calendar-day mr-3"></i>
                                        {{ $dayName }}
                                        @if($dayKey === $currentDay)
                                            <span class="ml-3 bg-yellow-400 text-blue-900 text-sm px-3 py-1 rounded-full font-bold animate-pulse">
                                                <i class="fas fa-star mr-1"></i>Hari Ini
                                            </span>
                                        @endif
                                    </h2>
                                    <span class="text-white text-lg font-semibold">
                                        {{ $schedules[$dayKey]->count() }} Sesi
                                    </span>
                                </div>
                                
                                <div class="p-6 bg-gradient-to-br from-white to-gray-50">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                                        @foreach($schedules[$dayKey]->sortBy('start_time') as $schedule)
                                            @php
                                                $now = now();
                                                $startTime = \Carbon\Carbon::parse($schedule->start_time);
                                                $endTime = \Carbon\Carbon::parse($schedule->end_time);
                                                $isNow = $dayKey === $currentDay && 
                                                         $now->format('H:i') >= $startTime->format('H:i') && 
                                                         $now->format('H:i') <= $endTime->format('H:i');
                                            @endphp
                                            
                                            <div class="bg-white rounded-xl p-5 border-2 {{ $isNow ? 'border-green-500 shadow-2xl ring-4 ring-green-200' : 'border-gray-200' }} hover:shadow-xl transition">
                                                @if($isNow)
                                                    <div class="mb-3 bg-green-500 text-white text-center py-2 rounded-lg font-bold animate-pulse">
                                                        <i class="fas fa-play-circle mr-2"></i>SEDANG BERLANGSUNG
                                                    </div>
                                                @endif
                                                
                                                <div class="flex items-center justify-between mb-4">
                                                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-bold text-lg">
                                                        <i class="fas fa-clock mr-2"></i>
                                                        {{ $startTime->format('H:i') }} - {{ $endTime->format('H:i') }}
                                                    </div>
                                                    <div class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                                        {{ $startTime->diffInMinutes($endTime) }} menit
                                                    </div>
                                                </div>
                                                
                                                <div class="space-y-3">
                                                    <div class="flex items-start">
                                                        <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                                                            <i class="fas fa-book text-indigo-600"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Mata Pelajaran</p>
                                                            <p class="text-lg font-bold text-gray-800">{{ $schedule->subject->name }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-start">
                                                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                                                            <i class="fas fa-users text-green-600"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Kelas</p>
                                                            <p class="text-base font-semibold text-gray-800">{{ $schedule->classModel->name }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="flex items-start">
                                                        <div class="bg-orange-100 p-2 rounded-lg mr-3">
                                                            <i class="fas fa-chalkboard-teacher text-orange-600"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide">Pengajar</p>
                                                            <p class="text-base font-semibold text-gray-800">{{ $schedule->teacher->name }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($schedule->notes)
                                                        <div class="mt-3 bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                                                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Catatan</p>
                                                            <p class="text-sm text-gray-700 italic">{{ $schedule->notes }}</p>
                                                        </div>
                                                    @endif
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
                <div class="bg-white rounded-2xl shadow-2xl p-16 text-center">
                    <i class="fas fa-calendar-times text-gray-300 text-8xl mb-6"></i>
                    <h2 class="text-3xl font-bold text-gray-600 mb-3">Belum Ada Jadwal</h2>
                    <p class="text-gray-500 text-lg">Ruangan ini belum memiliki jadwal yang terdaftar</p>
                </div>
            @endif
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-200 py-4 text-center text-gray-600 flex-shrink-0">
            <p class="text-sm">
                &copy; {{ date('Y') }} ElevOne-lab | Sistem Manajemen Jadwal
                <span class="mx-2">|</span>
                <a href="{{ route('admin.schedules.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i>Kembali ke Admin
                </a>
            </p>
        </footer>
    </div>

    <script>
        // Update clock
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const date = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            
            document.getElementById('current-time').textContent = time;
            document.getElementById('current-date').textContent = date;
        }
        
        updateClock();
        setInterval(updateClock, 1000);

        // Auto refresh every 5 minutes
        setTimeout(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>
