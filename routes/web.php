<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ErrorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JDoodleController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\RecoverPasswordController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StaticInfoController;
use App\Http\Controllers\SubmissionController;

Route::get('/', [ExerciseController::class, 'welcome']);

Route::get('/index', [ExerciseController::class, 'index'])->middleware('auth');

Route::get('/exercises/create', [ExerciseController::class, 'create'])->middleware('auth');

Route::post('/exercises', [ExerciseController::class, 'store'])->middleware('auth');

Route::get('/exercises/edit/{exercise}', [ExerciseController::class, 'edit'])->middleware('auth');

Route::put('/exercises/{exercise}', [ExerciseController::class, 'update'])->middleware('auth');

Route::post('/exercises/{exercise}/submit', [ExerciseController::class, 'submit'])->middleware('auth');

Route::delete('exercises/{exercise}', [ExerciseController::class, 'destroy'])->middleware('auth');

Route::get("/exercises/manage", [ExerciseController::class, 'manage'])->middleware('auth');

Route::post('/exercises/access/{exercise}', [ExerciseController::class, 'access'])->middleware('auth');




Route::get("/submissions", [SubmissionController::class, 'index'])->middleware('auth');

Route::get("/submissions/{exercise}/view/{submission}", [SubmissionController::class, 'view'])->middleware('auth');

Route::get("/manage/{exercise}/submissions/view", [SubmissionController::class, 'manage'])->middleware('auth');



Route::get("/forgot-password", [RecoverPasswordController::class, 'index'])->middleware('guest');

Route::post("/forgot-password-recovery", [RecoverPasswordController::class, 'store'])->middleware('guest');

Route::get("/reset-password/{token}", [RecoverPasswordController::class, 'edit'])->middleware('guest');

Route::post("/reset-password", [RecoverPasswordController::class, 'update'])->middleware('guest');


Route::get("/users/my-profile", [UserController::class, 'index'])->middleware('auth');

Route::get("/users/register", [UserController::class, 'create'])->middleware('guest');

Route::post("/users", [UserController::class, 'store'])->middleware('guest');

Route::get("/users/my-profile/edit", [UserController::class, 'edit'])->middleware('auth');

Route::put("/users/my-profile/users/{user}", [UserController::class, 'update'])->middleware('auth');


Route::get("/admin/user-list", [AdminController::class, 'index'])->middleware(['auth', 'admin']);

Route::get("/admin/profile/{id}", [AdminController::class, 'view'])->middleware(['auth', 'admin']);

Route::get("/admin/profile/edit/{id}", [AdminController::class, 'edit'])->middleware(['auth', 'admin']);

Route::put("/admin/profile/users/{id}", [AdminController::class, 'update'])->middleware(['auth', 'admin']);

Route::get("/admin/exercises/manage/{id}", [AdminController::class, 'manage'])->middleware(['auth', 'admin']);

Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->middleware(['auth', 'admin']);


Route::get("/users/login", [AuthenticationController::class, 'index'])->name('login')->middleware('guest');

Route::post("/users/authenticate", [AuthenticationController::class, 'store']);

Route::post("/logout", [AuthenticationController::class, 'logout'])->middleware('auth');


Route::get("auth/google", [SocialAuthController::class, 'googleLogin']);

Route::get("auth/google/callback", [SocialAuthController::class, 'googleStore']);

Route::get("auth/github", [SocialAuthController::class, 'githubLogin']);

Route::get("auth/github/callback", [SocialAuthController::class, 'githubStore']);


Route::get('/exercises/show/{exercise}', [ExerciseController::class, 'show'])->middleware('auth')->name('exercises.show');

Route::get('/setLanguage/{language}', [LanguageController::class, 'setLanguage'])->name('setLanguage');

Route::post('/proxy/jdoodle', [JDoodleController::class, 'execute']);

Route::fallback([ErrorController::class, 'notFoundErr']);
