<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    UserController,
    RoleController,
    PermissionController,
    SettingController,
    NotificationController,
    EgliseController,
    TribusController,
    DepartmentController,
    AbsenceController,
    VisiteController,
    AnnonceController,
    EventController
};

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
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Gestion des utilisateurs
    Route::resource('users', UserController::class)
        ->middleware('can:manage-users');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // Rôles & Permissions
    Route::prefix('roles_permissions')->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('permissions', PermissionController::class);
    });
    
    Route::resource('permissions', PermissionController::class)
        ->middleware('can:manage-permissions');

    // Paramètres
    Route::get('settings', [SettingController::class, 'index'])
        ->name('settings.index')
        ->middleware('can:view-settings');
    Route::put('settings', [SettingController::class, 'update'])
        ->name('settings.update')
        ->middleware('can:edit-settings');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::get('notifications/{notification}', [NotificationController::class, 'show'])
        ->name('notifications.show');
    Route::get('notifications/settings', [NotificationController::class, 'settings'])
        ->name('notifications.settings')
        ->middleware('can:edit-notification-settings');

    // Modules métiers
    Route::resource('eglises', EgliseController::class);
    Route::resource('tribus', TribusController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('absences', AbsenceController::class);
    Route::resource('visites', VisiteController::class);
    Route::resource('annonces', AnnonceController::class);
    //Route::resource('events', EventController::class); // Ajout d'une route pour les événements

    //Absences
    Route::get('absences/qr_scan', [AbsenceController::class, 'scan'])->name('absences.qr_scan');
    Route::get('/search', [AbsenceController::class, 'index'])->name('search');
    Route::get('/absences/qr-scan', [AbsenceController::class, 'scan'])->name('absences.qr-scan');
    Route::get('presence/scan/{user}', [AbsenceController::class, 'scan'])->name('presence.scan');
    Route::middleware(['auth', 'role:super-admin|pasteur|assistant-pasteur|patriarche'])->group(function () {
        Route::post('presence/validate/{user}', [AbsenceController::class, 'validatePresence'])->name('presence.validate');
    });

    // routes/web.php
    Route::post('absences', [AbsenceController::class, 'store'])->name('absences.store');
    //Route::get('/search', [SearchController::class, 'index'])->name('search');

    //visites
    Route::get('visites/calendar', [VisiteController::class, 'index'])->name('visites.calendar');
});

require __DIR__ . '/auth.php';
