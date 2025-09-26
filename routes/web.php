<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/theme', [AuthController::class, 'updateTheme'])->name('theme.update');

    // Profile routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // User management routes
    Route::resource('users', App\Http\Controllers\UserController::class);
    
    // Role management routes
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    
    // Location management routes
    Route::resource('locations', App\Http\Controllers\LocationController::class);
    
    // Event management routes
    Route::resource('events', App\Http\Controllers\EventController::class);
    Route::post('events/{event}/set-default', [App\Http\Controllers\EventController::class, 'setDefault'])->name('events.set-default');
    
    // Seat Map routes
    Route::get('seat-maps/{areaId}', [App\Http\Controllers\SeatMapController::class, 'show'])->name('seat-maps.show');
    Route::get('api/seat-maps/{areaId}', [App\Http\Controllers\SeatMapController::class, 'getSeatMap'])->name('seat-maps.get');
    Route::get('api/seat-maps/{areaId}/stats', [App\Http\Controllers\SeatMapController::class, 'getSeatStats'])->name('seat-maps.stats');
    
    // Sabeel management routes - custom routes first to avoid conflicts
    Route::get('sabeels/sample', [App\Http\Controllers\SabeelController::class, 'sample'])->name('sabeels.sample');
    Route::post('sabeels/import', [App\Http\Controllers\SabeelController::class, 'import'])->name('sabeels.import');
    Route::get('sabeels/import/preview', [App\Http\Controllers\SabeelController::class, 'importPreview'])->name('sabeels.import.preview');
    Route::post('sabeels/import/process', [App\Http\Controllers\SabeelController::class, 'processImport'])->name('sabeels.import.process');
    Route::resource('sabeels', App\Http\Controllers\SabeelController::class);
    
    // Mumin management routes - custom routes first to avoid conflicts
    Route::get('mumineen/sample', [App\Http\Controllers\MuminController::class, 'sample'])->name('mumineen.sample');
    Route::post('mumineen/import', [App\Http\Controllers\MuminController::class, 'import'])->name('mumineen.import');
    Route::get('mumineen/import/preview', [App\Http\Controllers\MuminController::class, 'importPreview'])->name('mumineen.import.preview');
    Route::post('mumineen/import/process', [App\Http\Controllers\MuminController::class, 'processImport'])->name('mumineen.import.process');
    Route::resource('mumineen', App\Http\Controllers\MuminController::class)->parameters([
        'mumineen' => 'mumin'
    ]);
    
    // Takhmeen management routes
    Route::get('takhmeen/sample', [App\Http\Controllers\TakhmeenController::class, 'sample'])->name('takhmeen.sample');
    Route::get('takhmeen/import', [App\Http\Controllers\TakhmeenController::class, 'import'])->name('takhmeen.import');
    Route::post('takhmeen/import', [App\Http\Controllers\TakhmeenController::class, 'importPreview'])->name('takhmeen.import.preview');
    Route::post('takhmeen/import/process', [App\Http\Controllers\TakhmeenController::class, 'processImport'])->name('takhmeen.import.process');
    Route::resource('takhmeen', App\Http\Controllers\TakhmeenController::class);
    
    // NOC management routes
    Route::get('noc/sample', [App\Http\Controllers\NocController::class, 'sample'])->name('noc.sample');
    Route::get('noc/import', [App\Http\Controllers\NocController::class, 'import'])->name('noc.import');
    Route::post('noc/import', [App\Http\Controllers\NocController::class, 'importPreview'])->name('noc.import.preview');
    Route::post('noc/import/process', [App\Http\Controllers\NocController::class, 'processImport'])->name('noc.import.process');
    Route::post('noc/{noc}/allocate', [App\Http\Controllers\NocController::class, 'allocate'])->name('noc.allocate');
    Route::post('noc/{noc}/revoke', [App\Http\Controllers\NocController::class, 'revoke'])->name('noc.revoke');
    Route::resource('noc', App\Http\Controllers\NocController::class);
    
    // Explicit route model binding for mumineen
    Route::model('mumin', App\Models\Mumin::class);
    
    // Admin/Dangerous operations - Clear All Data
    Route::prefix('admin')->group(function () {
        Route::get('clear-all-data', [App\Http\Controllers\ClearAllDataController::class, 'index'])->name('admin.clear-all-data');
        Route::post('clear-all-data/all', [App\Http\Controllers\ClearAllDataController::class, 'clearAll'])->name('admin.clear-all-data.all');
        Route::post('clear-all-data/mumineen', [App\Http\Controllers\ClearAllDataController::class, 'clearMumineen'])->name('admin.clear-all-data.mumineen');
        Route::post('clear-all-data/sabeels', [App\Http\Controllers\ClearAllDataController::class, 'clearSabeels'])->name('admin.clear-all-data.sabeels');
    });
});
