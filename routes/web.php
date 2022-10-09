<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BasicController;

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

Route::get('/', [BasicController::class,'home'])->name('home');

Route::get('/basic/select', [BasicController::class,'select'])->name('select');
Route::get('/basic/url', [BasicController::class,'url'])->name('url');

Route::get('/basic/upload', [BasicController::class,'upload'])->name('upload');
Route::get('/basic/file_download', [BasicController::class,'file_download'])->name('file.download');
Route::get('/basic/file_imgShow/{fileName}', [BasicController::class,'file_imgShow'])->name('file.imgShow');
Route::delete('/basic/file_delete', [BasicController::class,'file_delete'])->name('file.delete');
Route::post('/basic/upload_save', [BasicController::class,'upload_save'])->name('upload.save');
