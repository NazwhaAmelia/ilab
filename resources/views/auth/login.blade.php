<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ElevOne-lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen justify-center items-center">

    {{-- Header kecil --}}
    <header class="absolute top-0 left-0 right-0 flex justify-between items-center px-8 py-3 bg-white shadow-sm">
        <h1 class="text-xl font-bold text-blue-600 tracking-wide">ElevOne-lab</h1>
        <img src="{{ asset('i-lab/Logo-RPL2.png') }}" alt="Logo RPL" class="h-10 w-auto">
    </header>

    {{-- Container utama --}}
    <main class="bg-white shadow-md border border-gray-200 rounded-3xl p-10 w-full max-w-md mt-16">

        {{-- Judul --}}
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Selamat Datang 👋</h2>
            <p class="text-gray-500 text-sm">Silakan masuk untuk mengakses laboratorium digital</p>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Demo Info --}}
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <p class="text-sm text-blue-800">
                <strong>Demo Login:</strong><br>
                Email: <span class="font-mono">admin@elevone.com</span><br>
                Password: <span class="font-mono">password</span>
            </p>
        </div>

        {{-- Form login --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-gray-700 font-medium mb-1">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 @error('email') border-red-500 @enderror"
                       placeholder="admin@elevone.com">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Kata Sandi</label>
                <input type="password" id="password" name="password" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-gray-400 @error('password') border-red-500 @enderror"
                       placeholder="••••••••">
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" value="1"
                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-700">Ingat Saya</label>
            </div>

            {{-- Tombol --}}
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-xl hover:bg-blue-700 transition font-semibold shadow-md">
                Masuk
            </button>

            {{-- Link tambahan --}}
            <div class="text-center mt-4">
                <a href="/" class="text-sm text-blue-600 hover:underline">← Kembali ke Beranda</a>
            </div>
        </form>
    </main>
</body>
</html>
