@extends('layouts.admin')

@section('title', 'Edit Ruangan/Lab')
@section('page-title', 'Edit Ruangan/Lab')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('admin.classrooms.update', $classroom) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Ruangan --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Ruangan/Lab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $classroom->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Lab Komputer 1">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe --}}
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe <span class="text-red-500">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="classroom" {{ old('type', $classroom->type) == 'classroom' ? 'selected' : '' }}>Kelas</option>
                        <option value="laboratory" {{ old('type', $classroom->type) == 'laboratory' ? 'selected' : '' }}>Laboratorium</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kapasitas <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="capacity" 
                           name="capacity" 
                           value="{{ old('capacity', $classroom->capacity) }}"
                           required
                           min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="30">
                    @error('capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ID PIC --}}
                <div>
                    <label for="pic_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="pic_id" 
                           name="pic_id" 
                           value="{{ old('pic_id', $classroom->pic_id) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="NIP/ID">
                    @error('pic_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama PIC --}}
                <div>
                    <label for="pic_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Penanggung Jawab <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="pic_name" 
                           name="pic_name" 
                           value="{{ old('pic_name', $classroom->pic_name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Nama Lengkap">
                    @error('pic_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto PIC Saat Ini --}}
                @if($classroom->pic_photo)
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Penanggung Jawab Saat Ini
                    </label>
                    <img src="{{ asset('storage/' . $classroom->pic_photo) }}" 
                         alt="{{ $classroom->pic_name }}"
                         class="w-32 h-32 rounded-full object-cover">
                </div>
                @endif

                {{-- Upload Foto PIC Baru --}}
                <div class="md:col-span-2">
                    <label for="pic_photo" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ $classroom->pic_photo ? 'Ganti Foto Penanggung Jawab' : 'Foto Penanggung Jawab' }}
                    </label>
                    <input type="file" 
                           id="pic_photo" 
                           name="pic_photo" 
                           accept="image/jpeg,image/jpg,image/png"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           onchange="previewImage(event)">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG (Max: 2MB)</p>
                    @error('pic_photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    {{-- Preview --}}
                    <div id="preview" class="mt-3 hidden">
                        <img id="preview-image" src="" alt="Preview" class="w-32 h-32 rounded-full object-cover">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Deskripsi ruangan/lab...">{{ old('description', $classroom->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('admin.classrooms.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
