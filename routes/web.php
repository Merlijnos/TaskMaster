<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('teams', TeamController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('files', FileController::class);

    Route::post('/teams/{team}/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.members.remove');
    Route::patch('/teams/{team}/members/{user}/role', [TeamController::class, 'updateMemberRole'])->name('teams.members.update-role');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
