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

                {{-- Kode Ruangan --}}
                <div>
                    <label for="code" class="block text-sm font-semibold text-gray-700 mb-2">
                        Kode Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="code"
                           name="code"
                           value="{{ old('code', $classroom->code) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: LAB-1">
                    @error('code')
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
                        <option value="lab" {{ old('type', $classroom->type) == 'lab' ? 'selected' : '' }}>Laboratorium</option>
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

                {{-- Penanggung Jawab (Guru) --}}
                <div class="md:col-span-2">
                    <label for="pic_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Penanggung Jawab (Guru) <span class="text-red-500">*</span>
                    </label>
                    <select id="pic_id"
                            name="pic_id"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Pilih Guru Penanggung Jawab --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                {{ old('pic_id', $classroom->pic_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }} - {{ $teacher->nip ?? $teacher->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('pic_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
@endsection
