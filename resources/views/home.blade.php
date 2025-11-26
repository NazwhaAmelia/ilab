<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-Lab | {{ $selectedClassroom ? $selectedClassroom->name : 'Jadwal' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
        .dropdown-menu.show {
            display: block;
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">

    {{-- Full Screen Container --}}
    <div class="w-screen h-screen flex flex-col">

        {{-- Header Bar --}}
        <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 flex items-center justify-between shadow-lg z-10">
            <div class="flex items-center gap-4">
                <img src="{{ asset('i-lab/Logo-RPL2.png') }}" alt="Logo" class="h-12 w-auto">
                <div>
                    <h1 class="text-2xl font-bold">ElevOne-lab</h1>
                    <p class="text-sm opacity-90">Sistem Manajemen Jadwal</p>
                </div>
            </div>

            {{-- Dropdown Ruangan --}}
            <div class="relative dropdown">
                <button id="dropdownButton" class="bg-white text-gray-800 px-6 py-3 rounded-lg shadow-md hover:shadow-xl transition-all duration-200 flex items-center gap-3 font-semibold">
                    <i class="fas fa-door-open text-blue-600"></i>
                    <span>{{ $selectedClassroom ? $selectedClassroom->name : 'Pilih Ruangan' }}</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>
                <div id="dropdownMenu" class="dropdown-menu hidden absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-2xl overflow-hidden z-50 max-h-96 overflow-y-auto">
                    @foreach($classrooms as $classroom)
                        <a href="{{ route('home.classroom', $classroom->id) }}"
                           class="block px-6 py-4 hover:bg-blue-50 transition-colors {{ $selectedClassroom && $selectedClassroom->id == $classroom->id ? 'bg-blue-100 border-l-4 border-blue-600' : '' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-door-open text-gray-600"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $classroom->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($classroom->type) }} - Kapasitas: {{ $classroom->capacity }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Clock & Date --}}
            <div class="text-right">
                <p class="text-3xl font-bold" id="current-time">{{ now()->format('H:i:s') }}</p>
                <p class="text-sm opacity-90">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </header>

        @if($selectedClassroom)
            {{-- Main Content Area --}}
            <div class="flex-1 flex overflow-hidden min-h-0">

                {{-- Left Side - Teacher Photo & Current Status --}}
                <div class="w-2/5 p-6 flex flex-col gap-6 overflow-hidden min-h-0">

                    {{-- Teacher/PIC Photo Card (3/4 height) --}}
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border-4 border-gray-300 flex-1 relative">
                        @php
                            $displayPhoto = null;
                            $displayName = '';
                            $displayRole = '';

                            if ($currentSchedule && $currentSchedule->teacher) {
                                // Ada jadwal: tampilkan foto guru yang mengajar
                                $displayPhoto = $currentSchedule->teacher->photo;
                                $displayName = $currentSchedule->teacher->name;
                                $displayRole = 'Guru Pengajar';
                            } elseif ($selectedClassroom->pic) {
                                // Tidak ada jadwal: tampilkan foto PIC
                                $displayPhoto = $selectedClassroom->pic->photo;
                                $displayName = $selectedClassroom->pic->name;
                                $displayRole = 'Penanggung Jawab Ruangan';
                            }
                        @endphp

                        <div class="w-full h-full flex items-center justify-center {{ $displayPhoto ? 'bg-gray-100' : 'bg-gradient-to-br from-blue-100 to-purple-100' }}">
                            @if($displayPhoto)
                                <img src="{{ asset('storage/' . $displayPhoto) }}"
                                     alt="{{ $displayName }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-user text-9xl text-gray-400"></i>
                            @endif
                        </div>

                        {{-- Overlay nama PIC di kiri bawah --}}
                        @if($displayName)
                            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 backdrop-blur-sm p-4">
                                <h2 class="text-xl font-bold text-white truncate">{{ $displayName }}</h2>
                                <p class="text-gray-200 text-sm truncate">{{ $displayRole }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Current Status Card (fixed height to prevent overflow) --}}
                    <div class="bg-white rounded-3xl shadow-xl p-4 flex-none h-48 overflow-hidden flex flex-col">
                        @if($currentSchedule)
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse shrink-0"></div>
                                <h3 class="text-base font-bold text-gray-800 truncate">Sedang Berlangsung</h3>
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border-l-4 border-green-500 flex-1 overflow-hidden">
                                <h4 class="text-xl font-bold text-gray-800 mb-2 truncate">{{ $currentSchedule->subject->name }}</h4>
                                <div class="space-y-1 text-gray-700 text-sm">
                                    <p class="flex items-center gap-2 truncate">
                                        <i class="fas fa-users w-4 shrink-0"></i>
                                        <span class="font-semibold truncate">{{ $currentSchedule->classModel->name }}</span>
                                    </p>
                                    <p class="flex items-center gap-2 truncate">
                                        <i class="fas fa-clock w-4 shrink-0"></i>
                                        <span class="truncate">{{ \Carbon\Carbon::parse($currentSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($currentSchedule->end_time)->format('H:i') }}</span>
                                    </p>
                                    <p class="flex items-center gap-2 truncate">
                                        <i class="fas fa-chalkboard-teacher w-4 shrink-0"></i>
                                        <span class="truncate">{{ $currentSchedule->teacher->name }}</span>
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center flex-1 flex flex-col justify-center overflow-hidden">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2 shrink-0">
                                    <i class="fas fa-moon text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-base font-semibold text-gray-600 mb-1 truncate">Tidak Ada Kegiatan</h3>
                                <p class="text-xs text-gray-500 truncate">Tidak ada pelajaran saat ini</p>

                                @php
                                    $now = now()->format('H:i:s');
                                    $nextSchedule = $todaySchedules->where('start_time', '>', $now)->first();
                                @endphp

                                @if($nextSchedule)
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Jadwal Berikutnya:</p>
                                        <h4 class="text-sm font-bold text-gray-700 truncate">{{ $nextSchedule->subject->name }}</h4>
                                        <p class="text-xs text-gray-600">Pukul {{ \Carbon\Carbon::parse($nextSchedule->start_time)->format('H:i') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Side - Schedule List --}}
                <div class="flex-1 p-6 overflow-hidden min-h-0">
                    <div class="bg-white rounded-3xl shadow-xl h-full overflow-hidden flex flex-col">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-5 shrink-0">
                            <h3 class="text-xl font-bold mb-1 truncate">Jadwal Hari Ini - {{ $todayName }}</h3>
                            <p class="text-sm opacity-90 truncate">{{ $selectedClassroom->name }} ({{ ucfirst($selectedClassroom->type) }})</p>
                        </div>

                        <div class="flex-1 overflow-y-auto p-5 scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
                            @if($todaySchedules->count() > 0)
                                <div class="space-y-3">
                                    @foreach($todaySchedules as $schedule)
                                        @php
                                            $isActive = false;
                                            $isPast = false;
                                            $now = now()->format('H:i:s');
                                            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s');
                                            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H:i:s');

                                            if ($now >= $startTime && $now < $endTime) {
                                                $isActive = true;
                                            } elseif ($now >= $endTime) {
                                                $isPast = true;
                                            }
                                        @endphp

                                        <div class="rounded-xl p-4 border-2 transition-all duration-300 {{ $isActive ? 'bg-gradient-to-r from-green-500 to-green-600 text-white border-green-600 shadow-lg' : ($isPast ? 'bg-gray-50 border-gray-200 opacity-60' : 'bg-white border-blue-200 hover:border-blue-400 hover:shadow-md') }}">
                                            <div class="flex items-center justify-between mb-2 gap-2">
                                                <h4 class="text-lg font-bold {{ $isActive ? 'text-white' : 'text-gray-800' }} truncate flex-1">
                                                    {{ $schedule->subject->name }}
                                                </h4>
                                                @if($isActive)
                                                    <span class="bg-white text-green-600 px-2 py-1 rounded-full text-xs font-bold flex items-center gap-1 shrink-0">
                                                        <span class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></span>
                                                        LIVE
                                                    </span>
                                                @elseif($isPast)
                                                    <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs font-semibold shrink-0">
                                                        Selesai
                                                    </span>
                                                @else
                                                    <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-semibold shrink-0">
                                                        Akan Datang
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="grid grid-cols-2 gap-2 text-sm">
                                                <div class="flex items-center gap-2 {{ $isActive ? 'text-white' : 'text-gray-600' }} truncate">
                                                    <i class="fas fa-clock w-4 shrink-0"></i>
                                                    <span class="truncate">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 {{ $isActive ? 'text-white' : 'text-gray-600' }} truncate">
                                                    <i class="fas fa-users w-4 shrink-0"></i>
                                                    <span class="truncate">{{ $schedule->classModel->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 col-span-2 {{ $isActive ? 'text-white' : 'text-gray-600' }} truncate">
                                                    <i class="fas fa-chalkboard-teacher w-4 shrink-0"></i>
                                                    <span class="truncate">{{ $schedule->teacher->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <div class="text-center">
                                        <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-calendar-xmark text-5xl text-gray-400"></i>
                                        </div>
                                        <h4 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Jadwal</h4>
                                        <p class="text-gray-500">Tidak ada jadwal untuk ruangan ini hari ini</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- No Classroom Selected --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-door-open text-5xl text-gray-400"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-700 mb-2">Pilih Ruangan</h2>
                    <p class="text-gray-500">Silakan pilih ruangan dari dropdown di atas</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Scripts --}}
    <script>
        // Dropdown toggle dengan click
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdownButton && dropdownMenu) {
            dropdownButton.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        }

        // Update jam real-time
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);

        // Auto refresh setiap 2 menit untuk update jadwal
        setTimeout(() => {
            location.reload();
        }, 2 * 60 * 1000);
    </script>
</body>
</html>
