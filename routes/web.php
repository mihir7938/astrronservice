<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;

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

Route::get('/', [AuthController::class, 'getLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('authenticate');
Route::get('/register', [AuthController::class, 'getRegister'])->name('register');
Route::post('/create', [AuthController::class, 'create'])->name('create');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/complain', [PageController::class, 'complain'])->name('complain')->middleware('auth');
Route::post('/complain/save', [PageController::class, 'saveComplain'])->name('complain.save')->middleware('auth');

Route::group(['prefix' => 'password'], function () {
    Route::get('/forget', [AuthController::class, 'forgetPassword'])->name('forget_password');
    Route::post('/reset', [AuthController::class, 'resetPassword'])->name('check_password_reset');
    Route::get('/reset/{token}', [AuthController::class, 'getChangePassword'])->name('reset_password_link');
    Route::post('/reset/new/{token}', [AuthController::class, 'postChangePassword'])->name('change_password');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/complains', [AdminController::class, 'getComplains'])->name('admin.complains');
    Route::post('/fetch-complains', [AdminController::class, 'fetchComplainsByFilter'])->name('admin.complains.fetch');
    Route::get('/complains/edit/{id}', [AdminController::class, 'editComplain'])->name('admin.complains.edit');
    Route::post('/complains/update', [AdminController::class, 'updateComplain'])->name('admin.complains.update.save');
    Route::get('/complains/delete/{id}', [AdminController::class, 'deleteComplain'])->name('admin.complains.delete');
    Route::get('/complains/log/{id}', [AdminController::class, 'logComplain'])->name('admin.complains.log');
    Route::post('/complains/image/delete', [AdminController::class, 'deleteImage'])->name('admin.complains.image.delete');
    Route::get('/deleted-complains', [AdminController::class, 'deletedComplains'])->name('admin.deleted.complains');
    Route::get('/issues', [AdminController::class, 'issues'])->name('admin.issues');
    Route::get('/issues/add', [AdminController::class, 'addIssue'])->name('admin.issues.add');
    Route::post('/issues/save', [AdminController::class, 'saveIssue'])->name('admin.issues.add.save');
    Route::get('/issues/edit/{id}', [AdminController::class, 'editIssue'])->name('admin.issues.edit');
    Route::post('/issues/update', [AdminController::class, 'updateIssue'])->name('admin.issues.update.save');
    Route::get('/issues/delete/{id}', [AdminController::class, 'deleteIssue'])->name('admin.issues.delete');
    Route::get('/solutions', [AdminController::class, 'solutions'])->name('admin.solutions');
    Route::get('/solutions/add', [AdminController::class, 'addSolution'])->name('admin.solutions.add');
    Route::post('/solutions/save', [AdminController::class, 'saveSolution'])->name('admin.solutions.add.save');
    Route::get('/solutions/edit/{id}', [AdminController::class, 'editSolution'])->name('admin.solutions.edit');
    Route::post('/solutions/update', [AdminController::class, 'updateSolution'])->name('admin.solutions.update.save');
    Route::get('/solutions/delete/{id}', [AdminController::class, 'deleteSolution'])->name('admin.solutions.delete');
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/products/add', [AdminController::class, 'addProduct'])->name('admin.products.add');
    Route::post('/products/save', [AdminController::class, 'saveProduct'])->name('admin.products.add.save');
    Route::get('/products/edit/{id}', [AdminController::class, 'editProduct'])->name('admin.products.edit');
    Route::post('/products/update', [AdminController::class, 'updateProduct'])->name('admin.products.update.save');
    Route::get('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::get('/users', [AdminController::class, 'getUsers'])->name('admin.users');
    Route::post('/fetch-users', [AdminController::class, 'fetchUsers'])->name('admin.users.result');
    Route::get('/users/add', [AdminController::class, 'addUser'])->name('admin.users.add');
    Route::post('/users/save', [AdminController::class, 'saveUser'])->name('admin.users.add.save');
    Route::get('/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::post('/users/update', [AdminController::class, 'updateUser'])->name('admin.users.update.save');
    Route::get('/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::group(['prefix' => 'services', 'middleware' => 'service'], function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/complains', [ServiceController::class, 'getComplains'])->name('services.complains');
    Route::post('/fetch-complains', [ServiceController::class, 'fetchComplainsByFilter'])->name('services.complains.fetch');
    Route::get('/complains/edit/{id}', [ServiceController::class, 'editComplain'])->name('services.complains.edit');
    Route::post('/complains/update', [ServiceController::class, 'updateComplain'])->name('services.complains.update.save');
    Route::post('/complains/image/delete', [ServiceController::class, 'deleteImage'])->name('services.complains.image.delete');
});   

Route::group(['prefix' => 'users', 'middleware' => 'user'], function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/complains/view/{id}', [UserController::class, 'viewComplain'])->name('users.complains.view');
}); 