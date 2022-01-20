<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function(){

    Route::get('/dashboard', function(){
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/members', [
        MemberController::class,
        'showMembers'
    ])->name('members');

    Route::post('/delete-member', [
        MemberController::class,
        'deleteMembers'
    ])->name('delete-member');

    Route::post('/bulk-save-members', [
        MemberController::class,
        'bulkSaveMembers'
    ])->name('bulk-save-members');

    Route::get('/export-members', [
        MemberController::class,
        'ExportMembers'
    ])->name('export-members');

    Route::get('/import-members', function(){
        return Inertia::render('BulkImportMembers');
    })->name('import-members');

    Route::get('/member/{id}', [
        MemberController::class,
        'viewMember'
    ])->name('view-member');

    Route::post('/edit-member', [
        MemberController::class,
        'updateMember'
    ])->name('edit-member');

    Route::get('/add-member', function(){
        return Inertia::render('AddMember');
    })->name('add-member');

    Route::post('/save-member', [
        MemberController::class,
        'saveMember'
    ])->name('save-member');

});
