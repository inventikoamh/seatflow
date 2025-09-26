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
            $exitCode = Artisan::call('migrate');
            
            return back()->with('success', "All migrations completed successfully! (exit code: {$exitCode})");
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
            
            $exitCode = Artisan::call('migrate', ['--path' => $migration]);
            
            return back()->with('success', "Migration {$migration} completed successfully! (exit code: {$exitCode})");
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
            $exitCode = Artisan::call('migrate:rollback', ['--step' => 1]);
            
            return back()->with('success', "Migration rolled back successfully! (exit code: {$exitCode})");
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
            $exitCode = Artisan::call('db:seed');
            
            return back()->with('success', "All seeders completed successfully! (exit code: {$exitCode})");
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
            
            $exitCode = Artisan::call('db:seed', ['--class' => $seeder]);
            
            return back()->with('success', "Seeder {$seeder} completed successfully! (exit code: {$exitCode})");
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
            $messages = [];
            
            // Check if public/storage exists and delete it
            $publicStoragePath = public_path('storage');
            if (File::exists($publicStoragePath)) {
                if (is_link($publicStoragePath)) {
                    unlink($publicStoragePath);
                    $messages[] = "Removed existing symbolic link: {$publicStoragePath}";
                } elseif (PHP_OS_FAMILY === 'Windows') {
                    // On Windows, check if it's a junction
                    try {
                        $command = "Get-Item '{$publicStoragePath}' | Select-Object -ExpandProperty LinkType";
                        $junctionCheck = shell_exec("powershell -Command \"{$command}\"");
                        if (trim($junctionCheck) === 'Junction') {
                            // Remove junction using PowerShell
                            $removeCommand = "Remove-Item '{$publicStoragePath}' -Force";
                            shell_exec("powershell -Command \"{$removeCommand}\"");
                            $messages[] = "Removed existing junction: {$publicStoragePath}";
                        } else {
                            File::deleteDirectory($publicStoragePath);
                            $messages[] = "Removed existing directory: {$publicStoragePath}";
                        }
                    } catch (\Exception $e) {
                        File::deleteDirectory($publicStoragePath);
                        $messages[] = "Removed existing directory: {$publicStoragePath}";
                    }
                } else {
                    // On Linux/Mac, check if it's a symbolic link using ls command
                    try {
                        $output = shell_exec("ls -la '{$publicStoragePath}' 2>/dev/null");
                        if ($output && strpos($output, '->') !== false) {
                            // It's a symbolic link, remove it
                            unlink($publicStoragePath);
                            $messages[] = "Removed existing symbolic link: {$publicStoragePath}";
                        } else {
                            File::deleteDirectory($publicStoragePath);
                            $messages[] = "Removed existing directory: {$publicStoragePath}";
                        }
                    } catch (\Exception $e) {
                        // Fallback: if shell_exec is disabled, try alternative methods
                        try {
                            $realPath = realpath($publicStoragePath);
                            if ($realPath && $realPath !== $publicStoragePath) {
                                // It's likely a symbolic link, try to remove it
                                unlink($publicStoragePath);
                                $messages[] = "Removed existing symbolic link: {$publicStoragePath}";
                            } else {
                                File::deleteDirectory($publicStoragePath);
                                $messages[] = "Removed existing directory: {$publicStoragePath}";
                            }
                        } catch (\Exception $e2) {
                            File::deleteDirectory($publicStoragePath);
                            $messages[] = "Removed existing directory: {$publicStoragePath}";
                        }
                    }
                }
            }

            // Create the storage link
            $exitCode = Artisan::call('storage:link');
            $messages[] = "Storage link command executed (exit code: {$exitCode})";
            
            // Verify the link was created
            $isLinked = false;
            $target = null;
            
            if (File::exists($publicStoragePath)) {
                if (is_link($publicStoragePath)) {
                    $isLinked = true;
                    $target = readlink($publicStoragePath);
                } elseif (PHP_OS_FAMILY === 'Windows') {
                    // Check if it's a Windows junction
                    try {
                        $command = "Get-Item '{$publicStoragePath}' | Select-Object -ExpandProperty LinkType";
                        $output = shell_exec("powershell -Command \"{$command}\"");
                        if (trim($output) === 'Junction') {
                            $isLinked = true;
                            $targetCommand = "Get-Item '{$publicStoragePath}' | Select-Object -ExpandProperty Target";
                            $targetOutput = shell_exec("powershell -Command \"{$targetCommand}\"");
                            $target = trim($targetOutput);
                        }
                    } catch (\Exception $e) {
                        // Fallback check
                    }
                } else {
                    // On Linux/Mac, check if it's a symbolic link using ls command
                    try {
                        $output = shell_exec("ls -la '{$publicStoragePath}' 2>/dev/null");
                        if ($output && strpos($output, '->') !== false) {
                            $isLinked = true;
                            // Extract target from ls output
                            preg_match('/->\s+(.+)$/', $output, $matches);
                            if (isset($matches[1])) {
                                $target = trim($matches[1]);
                            }
                        }
                    } catch (\Exception $e) {
                        // Fallback: if shell_exec is disabled, try alternative methods
                        try {
                            $realPath = realpath($publicStoragePath);
                            if ($realPath && $realPath !== $publicStoragePath) {
                                $isLinked = true;
                                $target = $realPath;
                            }
                        } catch (\Exception $e2) {
                            // Final fallback check
                        }
                    }
                }
            }
            
            if ($isLinked && $target) {
                $messages[] = "Storage link created successfully!";
                $messages[] = "Link: {$publicStoragePath} -> {$target}";
            } else {
                $messages[] = "Warning: Storage link may not have been created properly.";
            }
            
            return back()->with('success', implode("\n", $messages));
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
                'config:clear' => 'Configuration cache',
                'cache:clear' => 'Application cache',
                'view:clear' => 'View cache',
                'route:clear' => 'Route cache',
            ];

            $messages = [];

            foreach ($commands as $command => $description) {
                $exitCode = Artisan::call($command);
                $messages[] = "✓ {$description} cleared (exit code: {$exitCode})";
            }
            
            $messages[] = "\nAll caches cleared successfully!";
            
            return back()->with('success', implode("\n", $messages));
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
            $exitCode = Artisan::call('migrate:status');
            
            // For now, return empty array since we can't easily capture output
            // In a real implementation, you might want to use a different approach
            return [];
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
        $publicStorageExists = File::exists($publicStoragePath);
        
        // Check if it's a link (cross-platform)
        $isLinked = false;
        $linkTarget = null;
        
        if ($publicStorageExists) {
            // Try PHP's is_link first (works on Linux/Mac)
            if (is_link($publicStoragePath)) {
                $isLinked = true;
                $linkTarget = readlink($publicStoragePath);
            } else {
                // On Windows, check if it's a junction using PowerShell
                if (PHP_OS_FAMILY === 'Windows') {
                    try {
                        $command = "Get-Item '{$publicStoragePath}' | Select-Object -ExpandProperty LinkType";
                        $output = shell_exec("powershell -Command \"{$command}\"");
                        if (trim($output) === 'Junction') {
                            $isLinked = true;
                            // Get the target
                            $targetCommand = "Get-Item '{$publicStoragePath}' | Select-Object -ExpandProperty Target";
                            $targetOutput = shell_exec("powershell -Command \"{$targetCommand}\"");
                            $linkTarget = trim($targetOutput);
                        }
                    } catch (\Exception $e) {
                        // Fallback: if it exists but isn't a regular directory, assume it's linked
                        if (!is_dir($publicStoragePath)) {
                            $isLinked = true;
                        }
                    }
                } else {
                    // On Linux/Mac, check if it's a symbolic link using ls command
                    try {
                        $output = shell_exec("ls -la '{$publicStoragePath}' 2>/dev/null");
                        if ($output && strpos($output, '->') !== false) {
                            $isLinked = true;
                            // Extract target from ls output
                            preg_match('/->\s+(.+)$/', $output, $matches);
                            if (isset($matches[1])) {
                                $linkTarget = trim($matches[1]);
                            }
                        }
                    } catch (\Exception $e) {
                        // Fallback: if shell_exec is disabled, try alternative methods
                        // Check if it's a symbolic link by trying to read it
                        try {
                            $realPath = realpath($publicStoragePath);
                            if ($realPath && $realPath !== $publicStoragePath) {
                                $isLinked = true;
                                $linkTarget = $realPath;
                            }
                        } catch (\Exception $e2) {
                            // Final fallback: if it exists but isn't a regular directory, assume it's linked
                            if (!is_dir($publicStoragePath)) {
                                $isLinked = true;
                            }
                        }
                    }
                }
            }
        }
        
        // Get actual storage size (not the link size)
        $actualStorageSize = 0;
        if ($publicStorageExists) {
            if ($isLinked && $linkTarget) {
                // If it's a link, get the size of the target directory
                if (File::exists($linkTarget)) {
                    $actualStorageSize = $this->getDirectorySize($linkTarget);
                }
            } else {
                // If it's a regular directory, get its size
                $actualStorageSize = $this->getDirectorySize($publicStoragePath);
            }
        }
        
        return [
            'storage_exists' => File::exists($storagePath),
            'storage_size' => File::exists($storagePath) ? $this->getDirectorySize($storagePath) : 0,
            'public_storage_exists' => $publicStorageExists,
            'public_storage_size' => $actualStorageSize,
            'is_linked' => $isLinked,
            'link_target' => $linkTarget,
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