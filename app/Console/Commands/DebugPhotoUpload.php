<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DebugPhotoUpload extends Command
{
    protected $signature = 'debug:photo';

    protected $description = 'Debug photo upload configuration';

    public function handle()
    {
        $this->info('=== Photo Upload Debug Info ===');
        $this->newLine();

        // 1. Cek konfigurasi filesystem
        $this->info('1. Filesystem Configuration:');
        $this->line('   Default disk: '.config('filesystems.default'));
        $this->line('   Public disk root: '.config('filesystems.disks.public.root'));
        $this->newLine();

        // 2. Cek path storage
        $paths = [
            'storage_path()' => storage_path(),
            'storage/app' => storage_path('app'),
            'storage/app/public' => storage_path('app/public'),
            'storage/app/public/teachers' => storage_path('app/public/teachers'),
            'public_path()' => public_path(),
            'public/storage' => public_path('storage'),
        ];

        $this->info('2. Storage Paths:');
        foreach ($paths as $label => $path) {
            $exists = file_exists($path);
            $writable = $exists && is_writable($path);
            $status = $exists ? 'EXISTS' : 'NOT FOUND';
            $permission = $writable ? '(writable)' : ($exists ? '(NOT writable)' : '');
            $this->line("   {$label}: {$status} {$permission}");
            if ($exists) {
                $this->line("      {$path}");
            }
        }
        $this->newLine();

        // 3. Cek symlink
        $symlinkPath = public_path('storage');
        $this->info('3. Symlink Check:');
        if (is_link($symlinkPath)) {
            $this->line('   ✅ Symlink exists');
            $this->line('   Target: '.readlink($symlinkPath));
        } elseif (is_dir($symlinkPath)) {
            $this->line('   ⚠️  Directory exists (not a symlink)');
        } else {
            $this->line('   ❌ Symlink not found');
            $this->warn('   Run: php artisan storage:link');
        }
        $this->newLine();

        // 4. Cek permission (Windows)
        $testPath = storage_path('app/public/teachers');
        $this->info('4. Permission Test:');
        if (! file_exists($testPath)) {
            $this->line('   Creating teachers folder...');
            if (mkdir($testPath, 0755, true)) {
                $this->line('   ✅ Folder created successfully');
            } else {
                $this->error('   ❌ Failed to create folder');
            }
        } else {
            $this->line('   ✅ Teachers folder exists');
        }

        // Test write
        $testFile = $testPath.'/test.txt';
        if (file_put_contents($testFile, 'test')) {
            $this->line('   ✅ Write test successful');
            unlink($testFile);
        } else {
            $this->error('   ❌ Cannot write to folder');
        }
        $this->newLine();

        // 5. Cek PHP upload settings
        $this->info('5. PHP Upload Configuration:');
        $this->line('   file_uploads: '.(ini_get('file_uploads') ? 'ON' : 'OFF'));
        $this->line('   upload_max_filesize: '.ini_get('upload_max_filesize'));
        $this->line('   post_max_size: '.ini_get('post_max_size'));
        $this->line('   max_file_uploads: '.ini_get('max_file_uploads'));
        $this->line('   upload_tmp_dir: '.(ini_get('upload_tmp_dir') ?: sys_get_temp_dir()));
        $this->newLine();

        // 6. Cek APP_URL
        $this->info('6. Application URL:');
        $this->line('   APP_URL: '.config('app.url'));
        $this->line('   Asset URL test: '.asset('storage/teachers/test.jpg'));
        $this->newLine();

        $this->info('=== End of Debug Info ===');

        return 0;
    }
}
