<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Events\TaskCreated;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/projects/{project}', function (Project $project) {
    // OrderStatusUpdated::dispatch(new Order(1));
    // return $project->tasks->pluck('description');
    return view('projects.show2', compact('project'));
})->name('home');


Route::post('/projects/{project}/tasks', function (Project $project) {

    $task = $project->tasks()->create([
        'description' => request('description'),
        'name' => request('name'),
        'user_id' => request('user_id'),
    ]);
    event(new TaskCreated($task));
    return $task;
});