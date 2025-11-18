<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $classroomId = $this->route('classroom')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', "unique:classrooms,code,{$classroomId}", 'max:50'],
            'type' => ['required', 'in:lab,classroom'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'pic_id' => ['required', 'exists:teachers,id'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama ruangan wajib diisi',
            'code.required' => 'Kode ruangan wajib diisi',
            'code.unique' => 'Kode ruangan sudah digunakan',
            'type.required' => 'Tipe ruangan wajib dipilih',
            'pic_id.required' => 'Penanggung jawab wajib dipilih',
            'pic_id.exists' => 'Guru yang dipilih tidak valid',
        ];
    }
}
