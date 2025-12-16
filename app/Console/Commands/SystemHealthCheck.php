<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SystemHealthCheck extends Command
{
    protected $signature = 'system:health-check';
    protected $description = 'Check system health and performance metrics';

    public function handle()
    {
        $this->info('Starting System Health Check...');
        $this->line('');

        // Database Health
        $this->checkDatabase();

        // Storage Health
        $this->checkStorage();

        // Cache Health
        $this->checkCache();

        // Memory Usage
        $this->checkMemory();

        // Disk Space
        $this->checkDiskSpace();

        // Application Performance
        $this->checkApplicationPerformance();

        $this->line('');
        $this->info('System Health Check Completed!');
    }

    private function checkDatabase()
    {
        $this->info('📊 Database Health:');
        
        try {
            $connection = DB::connection()->getPdo();
            $this->line('  ✅ Database Connection: OK');
            
            $tables = DB::select('SHOW TABLES');
            $this->line('  📋 Total Tables: ' . count($tables));
            
            $members = DB::table('members')->count();
            $this->line('  👥 Total Members: ' . $members);
            
            $transactions = DB::table('savings_loans')->count();
            $this->line('  💰 Total Transactions: ' . $transactions);
            
        } catch (\Exception $e) {
            $this->error('  ❌ Database Error: ' . $e->getMessage());
        }
        
        $this->line('');
    }

    private function checkStorage()
    {
        $this->info('💾 Storage Health:');
        
        try {
            $storagePath = storage_path();
            $freeSpace = disk_free_space($storagePath);
            $totalSpace = disk_total_space($storagePath);
            $usedSpace = $totalSpace - $freeSpace;
            $usagePercent = round(($usedSpace / $totalSpace) * 100, 2);
            
            $this->line('  📁 Storage Path: ' . $storagePath);
            $this->line('  💽 Free Space: ' . $this->formatBytes($freeSpace));
            $this->line('  📊 Usage: ' . $usagePercent . '%');
            
            if ($usagePercent > 80) {
                $this->error('  ⚠️  Warning: Storage usage is high!');
            } else {
                $this->line('  ✅ Storage Usage: Normal');
            }
            
        } catch (\Exception $e) {
            $this->error('  ❌ Storage Error: ' . $e->getMessage());
        }
        
        $this->line('');
    }

    private function checkCache()
    {
        $this->info('🗄️ Cache Health:');
        
        try {
            Cache::put('health_check_test', 'test_value', 60);
            $value = Cache::get('health_check_test');
            
            if ($value === 'test_value') {
                $this->line('  ✅ Cache: Working');
                Cache::forget('health_check_test');
            } else {
                $this->error('  ❌ Cache: Not Working');
            }
            
        } catch (\Exception $e) {
            $this->error('  ❌ Cache Error: ' . $e->getMessage());
        }
        
        $this->line('');
    }

    private function checkMemory()
    {
        $this->info('🧠 Memory Usage:');
        
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        $this->line('  📊 Current Usage: ' . $this->formatBytes($memoryUsage));
        $this->line('  ⚙️  Memory Limit: ' . $memoryLimit);
        
        if ($memoryUsage > 100 * 1024 * 1024) { // 100MB
            $this->error('  ⚠️  Warning: High memory usage!');
        } else {
            $this->line('  ✅ Memory Usage: Normal');
        }
        
        $this->line('');
    }

    private function checkDiskSpace()
    {
        $this->info('💿 Disk Space:');
        
        $freeSpace = disk_free_space('/');
        $totalSpace = disk_total_space('/');
        $usedSpace = $totalSpace - $freeSpace;
        $usagePercent = round(($usedSpace / $totalSpace) * 100, 2);
        
        $this->line('  💽 Total Space: ' . $this->formatBytes($totalSpace));
        $this->line('  📊 Used Space: ' . $this->formatBytes($usedSpace));
        $this->line('  💾 Free Space: ' . $this->formatBytes($freeSpace));
        $this->line('  📈 Usage: ' . $usagePercent . '%');
        
        if ($usagePercent > 85) {
            $this->error('  ⚠️  Critical: Disk space is very low!');
        } elseif ($usagePercent > 70) {
            $this->error('  ⚠️  Warning: Disk space is getting low!');
        } else {
            $this->line('  ✅ Disk Space: Normal');
        }
        
        $this->line('');
    }

    private function checkApplicationPerformance()
    {
        $this->info('⚡ Application Performance:');
        
        // Check Laravel version
        $laravelVersion = app()->version();
        $this->line('  🚀 Laravel Version: ' . $laravelVersion);
        
        // Check PHP version
        $phpVersion = PHP_VERSION;
        $this->line('  🐘 PHP Version: ' . $phpVersion);
        
        // Check if optimizations are enabled
        $configCached = app()->configurationIsCached();
        $routesCached = app()->routesAreCached();
        $eventsCached = app()->eventsAreCached();
        
        $this->line('  ⚙️  Config Cached: ' . ($configCached ? '✅ Yes' : '❌ No'));
        $this->line('  🛣️  Routes Cached: ' . ($routesCached ? '✅ Yes' : '❌ No'));
        $this->line('  🎯 Events Cached: ' . ($eventsCached ? '✅ Yes' : '❌ No'));
        
        if (!$configCached || !$routesCached) {
            $this->error('  ⚠️  Warning: Performance optimizations not enabled!');
        } else {
            $this->line('  ✅ Performance: Optimized');
        }
        
        $this->line('');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
