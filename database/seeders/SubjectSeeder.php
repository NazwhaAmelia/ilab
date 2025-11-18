<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['code' => 'MTK', 'name' => 'Matematika', 'description' => 'Pelajaran Matematika'],
            ['code' => 'BIN', 'name' => 'Bahasa Indonesia', 'description' => 'Pelajaran Bahasa Indonesia'],
            ['code' => 'BING', 'name' => 'Bahasa Inggris', 'description' => 'Pelajaran Bahasa Inggris'],
            ['code' => 'FIS', 'name' => 'Fisika', 'description' => 'Pelajaran Fisika'],
            ['code' => 'KIM', 'name' => 'Kimia', 'description' => 'Pelajaran Kimia'],
            ['code' => 'BIO', 'name' => 'Biologi', 'description' => 'Pelajaran Biologi'],
            ['code' => 'SEJ', 'name' => 'Sejarah', 'description' => 'Pelajaran Sejarah'],
            ['code' => 'GEO', 'name' => 'Geografi', 'description' => 'Pelajaran Geografi'],
            ['code' => 'EKO', 'name' => 'Ekonomi', 'description' => 'Pelajaran Ekonomi'],
            ['code' => 'SOS', 'name' => 'Sosiologi', 'description' => 'Pelajaran Sosiologi'],
            ['code' => 'PJOK', 'name' => 'Pendidikan Jasmani', 'description' => 'Pelajaran Pendidikan Jasmani dan Olahraga'],
            ['code' => 'SEN', 'name' => 'Seni Budaya', 'description' => 'Pelajaran Seni Budaya'],
            ['code' => 'PKN', 'name' => 'Pendidikan Kewarganegaraan', 'description' => 'Pelajaran Pendidikan Kewarganegaraan'],
            ['code' => 'PAI', 'name' => 'Pendidikan Agama Islam', 'description' => 'Pelajaran Pendidikan Agama Islam'],
            ['code' => 'PWK', 'name' => 'Pemrograman Web dan Perangkat Bergerak', 'description' => 'Mata pelajaran produktif RPL'],
            ['code' => 'PBO', 'name' => 'Pemrograman Berorientasi Objek', 'description' => 'Mata pelajaran produktif RPL'],
            ['code' => 'BDG', 'name' => 'Basis Data', 'description' => 'Mata pelajaran produktif RPL'],
            ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual', 'description' => 'Mata pelajaran produktif RPL'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
