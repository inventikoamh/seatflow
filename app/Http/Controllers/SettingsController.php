<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\BufferedOutput;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole('administrator')) {
                abort(403, 'This action is unauthorized.');
            }
            return $next($request);
        });
    }

    /**
     * Display the settings dashboard.
     */
    public function index(): View
    {
        return view('settings.index');
    }

    /**
     * Display migration management page.
     */
    public function migrations(): View
    {
        $migrations = $this->getMigrations();
        $pendingMigrations = $this->getPendingMigrations();
        
        return view('settings.migrations', compact('migrations', 'pendingMigrations'));
    }

    /**
     * Run all pending migrations.
     */
    public function runAllMigrations(): RedirectResponse
    {
        try {
            $output = new BufferedOutput();
            Artisan::call('migrate', [], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', 'All migrations completed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Migration failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Run specific migration.
     */
    public function runMigration(Request $request): RedirectResponse
    {
        $request->validate([
            'migration' => 'required|string',
        ]);

        try {
            $migration = $request->migration;
            $output = new BufferedOutput();
            
            Artisan::call('migrate', ['--path' => $migration], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', "Migration {$migration} completed successfully!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Migration failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Rollback last migration.
     */
    public function rollbackMigration(): RedirectResponse
    {
        try {
            $output = new BufferedOutput();
            Artisan::call('migrate:rollback', ['--step' => 1], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', 'Migration rolled back successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Rollback failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display seeder management page.
     */
    public function seeders(): View
    {
        $seeders = $this->getSeeders();
        
        return view('settings.seeders', compact('seeders'));
    }

    /**
     * Run all seeders.
     */
    public function runAllSeeders(): RedirectResponse
    {
        try {
            $output = new BufferedOutput();
            Artisan::call('db:seed', [], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', 'All seeders completed successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Seeding failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Run specific seeder.
     */
    public function runSeeder(Request $request): RedirectResponse
    {
        $request->validate([
            'seeder' => 'required|string',
        ]);

        try {
            $seeder = $request->seeder;
            $output = new BufferedOutput();
            
            Artisan::call('db:seed', ['--class' => $seeder], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', "Seeder {$seeder} completed successfully!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Seeding failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display storage management page.
     */
    public function storage(): View
    {
        $storageInfo = $this->getStorageInfo();
        
        return view('settings.storage', compact('storageInfo'));
    }

    /**
     * Create storage link.
     */
    public function createStorageLink(): RedirectResponse
    {
        try {
            // Check if public/storage exists and delete it
            $publicStoragePath = public_path('storage');
            if (File::exists($publicStoragePath)) {
                File::deleteDirectory($publicStoragePath);
            }

            // Create the storage link
            $output = new BufferedOutput();
            Artisan::call('storage:link', [], $output);
            
            $result = $output->fetch();
            
            return back()->with('success', 'Storage link created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Storage link creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Clear application cache.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            $commands = [
                'config:clear',
                'cache:clear',
                'view:clear',
                'route:clear',
            ];

            foreach ($commands as $command) {
                Artisan::call($command);
            }
            
            return back()->with('success', 'Application cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Cache clearing failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Get all migration files.
     */
    private function getMigrations(): array
    {
        $migrations = [];
        $migrationPath = database_path('migrations');
        
        if (File::exists($migrationPath)) {
            $files = File::files($migrationPath);
            
            foreach ($files as $file) {
                $migrations[] = [
                    'name' => $file->getFilename(),
                    'path' => 'database/migrations/' . $file->getFilename(),
                    'size' => $file->getSize(),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }
        
        return $migrations;
    }

    /**
     * Get pending migrations.
     */
    private function getPendingMigrations(): array
    {
        try {
            $output = new BufferedOutput();
            Artisan::call('migrate:status', [], $output);
            
            $result = $output->fetch();
            $lines = explode("\n", $result);
            $pending = [];
            
            foreach ($lines as $line) {
                if (strpos($line, 'Pending') !== false) {
                    $parts = preg_split('/\s+/', trim($line));
                    if (count($parts) >= 2) {
                        $pending[] = $parts[1];
                    }
                }
            }
            
            return $pending;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get all seeder files.
     */
    private function getSeeders(): array
    {
        $seeders = [];
        $seederPath = database_path('seeders');
        
        if (File::exists($seederPath)) {
            $files = File::files($seederPath);
            
            foreach ($files as $file) {
                $filename = $file->getFilename();
                if (strpos($filename, 'Seeder.php') !== false) {
                    $className = str_replace('.php', '', $filename);
                    $seeders[] = [
                        'name' => $filename,
                        'class' => $className,
                        'size' => $file->getSize(),
                        'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    ];
                }
            }
        }
        
        return $seeders;
    }

    /**
     * Get storage information.
     */
    private function getStorageInfo(): array
    {
        $storagePath = storage_path('app');
        $publicStoragePath = public_path('storage');
        
        return [
            'storage_exists' => File::exists($storagePath),
            'storage_size' => File::exists($storagePath) ? $this->getDirectorySize($storagePath) : 0,
            'public_storage_exists' => File::exists($publicStoragePath),
            'public_storage_size' => File::exists($publicStoragePath) ? $this->getDirectorySize($publicStoragePath) : 0,
            'is_linked' => File::exists($publicStoragePath) && is_link($publicStoragePath),
        ];
    }

    /**
     * Get directory size in bytes.
     */
    private function getDirectorySize($directory): int
    {
        $size = 0;
        if (File::exists($directory)) {
            foreach (File::allFiles($directory) as $file) {
                $size += $file->getSize();
            }
        }
        return $size;
    }

    /**
     * Format bytes to human readable format.
     */
    public static function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}