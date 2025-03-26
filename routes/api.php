<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BBCController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeOrDislikeController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [BBCController::class, 'index'])->name('bbc.index');
Route::post('/register', [AuthController::class, 'register']); 
Route::middleware('auth.basic')->post('/login', [AuthController::class, 'login']);
Route::middleware('auth.basic')->get('/logout', function (Request $request) {
    return response()->json([
        'message' => 'Logged out successfully'
    ], 401)->header('WWW-Authenticate', 'Basic realm="Logout"');
});

Route::middleware(['auth.basic', 'role:admin|author'])->get('/admin', function () {
    return response()->view('admin.index'); 
});
Route::middleware('auth.basic')->get('/admin/posts', [PostController::class, 'index']);
Route::middleware('auth.basic')->get('/admin/categories', [CategoryController::class, 'index']);
Route::get('email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('api.email.verify');

Route::middleware('auth.basic')->group(function () {
    Route::get('/bbc/category/{id}', [BBCController::class, 'byCategory']);
    Route::get('/bbc/single/{id}', [BBCController::class, 'single']);

    
    Route::middleware('role:admin')->group(function () {
        Route::get('/category', [CategoryController::class, 'index']);
        Route::post('/category/store', [CategoryController::class, 'store']);
        Route::put('/category/update/{category}', [CategoryController::class, 'update']);
        Route::delete('/category/destroy/{category}', [CategoryController::class, 'delete']);
    });
    
    Route::middleware('role:admin|author')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/post', [PostController::class, 'index']);
        Route::post('/post/store', [PostController::class, 'store']);
        Route::put('/post/update/{post}', [PostController::class, 'update']);
        Route::delete('/post/destroy/{post}', [PostController::class, 'delete']);
    });

    Route::post('/posts/{post}/like', [LikeOrDislikeController::class, 'like']);
    Route::post('/posts/{post}/dislike', [LikeOrDislikeController::class, 'dislike']);

    Route::post('/comments/store/{post}/comment', [CommentController::class, 'store']);
});
