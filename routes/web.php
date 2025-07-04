<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TaskController;
use App\Models\Person;
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
    return view('tasks');
});

Route::get('/personas/create', function () {
    return view('personas');  //muestra el form de personas como modal
});

Route::get('/personas/lista', function () {
    $personas = Person::all();
    return view('personaslist', compact('personas')); //da la lista de personas como modal
});
//Rutas api para task
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::post('/tasks/{task_id}/assign', [TaskController::class, 'assign_task']);
Route::post('/tasks/{taskId}/unassign', [TaskController::class, 'unassign']);

//rutas api para personas
Route::post('/personas', [PersonController::class, 'store']);
Route::get('/personas', [PersonController::class, 'index']);
Route::view('/personas/registro', 'personas'); // para ver esta vista