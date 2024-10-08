<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\JobApplicationController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jobs', [JobsController::class, 'index'])->name('jobs');
Route::get('/jobs/details/{id}', [JobsController::class, 'details'])->name('jobs.details');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'checkRole']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/edit-user/{id}', [UserController::class, 'editUser'])->name('admin.edit.user');
    Route::put('/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.update.user');
    Route::delete('/delete-user/', [UserController::class, 'deleteUser'])->name('admin.delete.user');
    Route::get('/jobs', [JobController::class, 'index'])->name('admin.jobs');
    Route::get('/edit-job/{id}', [JobController::class, 'editJob'])->name('admin.edit.job');
    Route::put('/update-job/{id}', [JobController::class, 'updateJob'])->name('admin.update.job');
    Route::delete('/delete-job/', [JobController::class, 'deleteJob'])->name('admin.delete.job');
    Route::get('/job-applications', [JobApplicationController::class, 'index'])->name('admin.job_applications');
    Route::delete('/delete-job-application/', [JobApplicationController::class, 'deleteJobApplication'])->name('admin.delete.job_application');
});


Route::group(['prefix' => 'account'], function () {

    //Guest Routes
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/register', [AccountController::class, 'register'])->name('account.register');
        Route::post('/process-register', [AccountController::class, 'processRegister'])->name('account.process.register');
        Route::get('/login', [AccountController::class, 'login'])->name('account.login');
        Route::post('/process-login', [AccountController::class, 'processLogin'])->name('account.process.login');
        Route::get('/forgot-password', [AccountController::class, 'forgotPassword'])->name('account.forgot.password');
        Route::post('/process-forgot-password', [AccountController::class, 'processForgotPassword'])->name('account.process.forgot.password');
        Route::get('/reset-password/{token}', [AccountController::class, 'resetPassword'])->name('account.resetPassword');
        Route::post('/process-reset-password/', [AccountController::class, 'processResetPassword'])->name('account.process.resetPassword');
    });

    //Authenticated Routes
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/update-profile', [AccountController::class, 'updateProfile'])->name('account.update.profile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('account.logout');
        Route::post('/update-profile-pic', [AccountController::class, 'updateProfilePic'])->name('account.update.profile.pic');
        Route::get('/create-job', [AccountController::class, 'createJob'])->name('account.create.job');
        Route::post('/save-job', [AccountController::class, 'saveJob'])->name('account.save.job');
        Route::get('/my-jobs', [AccountController::class, 'myJobs'])->name('account.my.jobs');
        Route::get('/edit-job/{id}', [AccountController::class, 'editJob'])->name('account.edit.job');
        Route::post('/update-job/{id}', [AccountController::class, 'updateJob'])->name('account.update.job');
        Route::post('/delete-job/', [AccountController::class, 'deleteJob'])->name('account.delete.job');
        Route::post('/job/apply', [JobsController::class, 'applyJob'])->name('applyJob');
        Route::get('/my-job/applications', [AccountController::class, 'myJobApplications'])->name('account.my.job.applications');
        Route::post('/remove-job-application/', [AccountController::class, 'removeJobApplication'])->name('account.remove.jobApplication');
        Route::post('/job/save', [JobsController::class, 'saveJob'])->name('saveJob');
        Route::get('/my-saved-jobs', [AccountController::class, 'mySavedJobs'])->name('account.my.saved.jobs');
        Route::post('/remove-saved-job/', [AccountController::class, 'removeSavedJOb'])->name('account.remove.savedJob');
        Route::post('/update-password/', [AccountController::class, 'updatePassword'])->name('account.update.password');
    });
});