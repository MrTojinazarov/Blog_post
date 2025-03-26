<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthPageController;
use App\Http\Controllers\UserInfoExportController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthPageController::class, 'RegisterPage'])->name('register');
Route::get('/login', [AuthPageController::class, 'LoginPage'])->name('login');
Route::get('/admin/export/user-post-statistics/{user_id}', [AdminController::class, 'exportUserPostStatistics'])->name('admin.export.user_post_statistics');
Route::get('/admin/export/user_info_pdf/{user_id}', [UserInfoExportController::class, 'exportUserInfoPDF'])->name('admin.export.user_info_pdf');
