<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup {--compress : Compress the backup file}';
    protected $description = 'Backup the application database';

    public function handle()
    {
        $this->info('Starting database backup...');
        
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            
            $filename = 'backup_' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/' . $filename);
            
            // Create backups directory if it doesn't exist
            $backupDir = dirname($path);
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            // Create backup using mysqldump
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($path)
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->info("✅ Database backup created: {$filename}");
                
                if ($this->option('compress')) {
                    $compressedFile = $path . '.gz';
                    $compressedCommand = "gzip {$path}";
                    exec($compressedCommand, $output, $returnCode);
                    
                    if ($returnCode === 0) {
                        $this->info("✅ Backup compressed: {$filename}.gz");
                        $filename .= '.gz';
                    }
                }
                
                // Clean old backups (keep last 7 days)
                $this->cleanOldBackups($backupDir);
                
                return 0;
            } else {
                $this->error('❌ Failed to create database backup');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Backup error: ' . $e->getMessage());
            return 1;
        }
    }
    
    private function cleanOldBackups($backupDir)
    {
        $files = glob($backupDir . '/*.sql*');
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                $fileAge = $now - filemtime($file);
                $daysOld = $fileAge / (60 * 60 * 24);
                
                if ($daysOld > 7) {
                    unlink($file);
                    $this->info("🗑️  Deleted old backup: " . basename($file));
                }
            }
        }
    }
}
