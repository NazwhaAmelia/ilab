<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>I-Lab | Jadwal Hari Ini</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-900 h-screen overflow-hidden flex flex-col">

    {{-- Header --}}
    <header class="flex items-center justify-between px-8 py-3 bg-white shadow-sm flex-shrink-0">
        <h1 class="text-xl font-bold text-blue-600 tracking-wide">ElevOne-lab</h1>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-700" id="current-time">{{ now()->format('H:i:s') }}</p>
                <p class="text-xs text-gray-500">{{ now()->format('d F Y') }}</p>
            </div>
            <img src="{{ asset('i-lab/Logo-RPL2.png') }}"
                 alt="Logo RPL"
                 class="h-10 w-auto">
        </div>
    </header>

    {{-- Main Layout --}}
    <main class="max-w-7xl mx-auto flex-1 grid grid-cols-2 grid-rows-6 gap-4 px-10 py-6 overflow-hidden">

        {{-- Kiri Atas - Foto Guru --}}
        <div class="bg-white rounded-3xl shadow-md border row-span-3 border-gray-200 flex items-center justify-center">
            @if($currentSchedule && $currentSchedule->teacher && $currentSchedule->teacher->photo)
                <img src="{{ asset('storage/' . $currentSchedule->teacher->photo) }}"
                    alt="{{ $currentSchedule->teacher->name }}"
                    class="w-72 h-52 rounded-2xl object-cover border-4 border-blue-500 shadow-md m-6">
            @elseif($firstSchedule && $firstSchedule->teacher && $firstSchedule->teacher->photo)
                <img src="{{ asset('storage/' . $firstSchedule->teacher->photo) }}"
                    alt="{{ $firstSchedule->teacher->name }}"
                    class="w-72 h-52 rounded-2xl object-cover border-4 border-gray-300 shadow-md m-6">
            @else
                <div class="w-72 h-52 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 border-4 border-gray-300 shadow-md m-6 flex items-center justify-center">
                    <i class="fas fa-user text-6xl text-blue-300"></i>
                </div>
            @endif
        </div>

        {{-- Kanan Atas - Nama Guru (diperkecil) --}}
        <div class="bg-white rounded-2xl shadow-md p-6 border row-span-2 border-gray-200 flex flex-col justify-center">
            @if($currentSchedule)
                <h2 class="text-2xl font-semibold text-gray-800">{{ $currentSchedule->teacher->name }}</h2>
                <p class="text-gray-500 text-base mt-1">Guru {{ $currentSchedule->subject->name }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ strtoupper($currentSchedule->classroom->name) }}</p>
                <div class="mt-3 inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold w-fit">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    Sedang Mengajar
                </div>
            @elseif($firstSchedule)
                <h2 class="text-2xl font-semibold text-gray-800">{{ $firstSchedule->teacher->name }}</h2>
                <p class="text-gray-500 text-base mt-1">Guru {{ $firstSchedule->subject->name }}</p>
                <p class="text-sm text-gray-400 mt-1">{{ strtoupper($firstSchedule->classroom->name) }}</p>
                <div class="mt-3 inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold w-fit">
                    <i class="fas fa-clock"></i>
                    Jadwal Pertama Hari Ini
                </div>
            @else
                <div class="text-center text-gray-400">
                    <i class="fas fa-calendar-xmark text-4xl mb-2"></i>
                    <p class="text-sm">Tidak ada jadwal hari ini</p>
                </div>
            @endif
        </div>

        {{-- 🔹 Kanan Bawah - Jadwal Hari Ini (2 kolom) 🔹 --}}
        <div class="bg-white rounded-3xl shadow-md p-8 border row-span-4 border-gray-200 flex flex-col items-start overflow-y-auto">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Jadwal Mata Pelajaran Hari Ini - {{ $todayName }}</h2>

            @if($todaySchedules->count() > 0)
                <div class="grid grid-cols-2 gap-3 w-full">
                    @foreach ($todaySchedules as $schedule)
                        @php
                            $isActive = false;
                            $now = now()->format('H:i:s');
                            $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i:s');
                            $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H:i:s');
                            if ($now >= $startTime && $now < $endTime) {
                                $isActive = true;
                            }
                        @endphp
                        <div class="p-4 rounded-2xl transition {{ $isActive ? 'bg-blue-500 text-white shadow-lg transform scale-105' : 'bg-gray-100 hover:bg-blue-100' }}">
                            <h3 class="font-semibold text-sm {{ $isActive ? 'text-white' : 'text-gray-800' }}">
                                {{ $schedule->subject->name }}
                            </h3>
                            <p class="text-xs mt-1 {{ $isActive ? 'text-blue-100' : 'text-gray-500' }}">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </p>
                            <p class="text-xs mt-1 {{ $isActive ? 'text-blue-50' : 'text-gray-400' }}">
                                {{ $schedule->classModel->name }}
                            </p>
                            @if($isActive)
                                <div class="mt-2 flex items-center gap-1 text-xs">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                    <span>Sedang Berlangsung</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="w-full text-center text-gray-400 py-8">
                    <i class="fas fa-calendar-xmark text-5xl mb-3"></i>
                    <p>Tidak ada jadwal untuk hari ini</p>
                </div>
            @endif
        </div>

        {{-- 🔹 Kiri Bawah - Pelajaran Sedang Berlangsung 🔹 --}}
        <div class="bg-white rounded-3xl shadow-md p-8 border row-span-3 border-gray-200 overflow-hidden">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Pelajaran Sedang Berlangsung</h2>
            <p class="text-gray-500 mb-4">Jadwal Pelajaran yang sedang berlangsung:</p>

            @if($currentSchedule)
                <div class="bg-blue-100 border-l-4 border-blue-500 p-5 rounded-2xl">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">{{ $currentSchedule->subject->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $currentSchedule->classModel->name }} - {{ $currentSchedule->classroom->name }}</p>
                            <p class="text-gray-500 text-sm mt-2">
                                <i class="fas fa-clock mr-1"></i>
                                Pukul {{ \Carbon\Carbon::parse($currentSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($currentSchedule->end_time)->format('H:i') }}
                            </p>
                            <p class="text-gray-600 text-sm mt-2">
                                <i class="fas fa-user mr-1"></i>
                                {{ $currentSchedule->teacher->name }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                            LIVE
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-100 border-l-4 border-gray-300 p-5 rounded-2xl text-center">
                    <i class="fas fa-moon text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500 text-sm">Tidak ada pelajaran yang sedang berlangsung saat ini</p>
                    @if($todaySchedules->count() > 0)
                        @php
                            $nextSchedule = $todaySchedules->where('start_time', '>', now()->format('H:i:s'))->first();
                        @endphp
                        @if($nextSchedule)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-400 mb-2">Pelajaran Berikutnya:</p>
                                <h4 class="font-semibold text-gray-700">{{ $nextSchedule->subject->name }}</h4>
                                <p class="text-xs text-gray-500 mt-1">Pukul {{ \Carbon\Carbon::parse($nextSchedule->start_time)->format('H:i') }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-gray-600 text-sm flex-shrink-0">
        &copy; {{ date('Y') }} ElevOne-lab — Dikembangkan oleh <span class="font-semibold text-blue-600">Tim RPL</span>
    </footer>

    {{-- Auto Refresh & Real-time Clock --}}
    <script>
        // Update jam real-time
        function updateClock() {
            const now = new Date();
            const timeString = now.toTimeString().split(' ')[0];
            document.getElementById('current-time').textContent = timeString;
        }
        
        setInterval(updateClock, 1000);
        
        // Auto refresh setiap 5 menit untuk update jadwal
        setTimeout(() => {
            location.reload();
        }, 5 * 60 * 1000);
    </script>
</body>
</html>
