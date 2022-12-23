<?php

use Illuminate\Support\Facades\Route;

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

//main
Route::get('/', [\App\Http\Controllers\Home::class, "index"]);

//admin
Route::redirect("/admin", "/admin/gallery");
Route::get("/admin/gallery", [\App\Http\Controllers\Admin\Gallery::class, "index"])->middleware("auth");
Route::get("/admin/gallery/upload", [\App\Http\Controllers\Admin\Gallery::class, "upload"])->middleware("auth");
Route::get('/admin/login', [\App\Http\Controllers\Admin\Auth::class, "login"])->name("admin_login")->middleware("guest");
Route::get("/admin/logout", function(){
    \Illuminate\Support\Facades\Auth::logout();
    \Illuminate\Support\Facades\Request::session()->invalidate();
    \Illuminate\Support\Facades\Request::session()->regenerateToken();
    return redirect('/admin/login');
});

//admin ajax
Route::post('/admin/ajax/login', [\App\Http\Controllers\Admin\Auth::class, "ajaxLogin"]);
Route::post('/admin/ajax/upload', [\App\Http\Controllers\Admin\Gallery::class, "ajaxUpload"])->middleware("auth");;
Route::post('/admin/ajax/toggle-image', [\App\Http\Controllers\Admin\Gallery::class, "ajaxToggleImage"])->middleware("auth");;
Route::post('/admin/ajax/delete-image', [\App\Http\Controllers\Admin\Gallery::class, "ajaxDeleteImage"])->middleware("auth");;
