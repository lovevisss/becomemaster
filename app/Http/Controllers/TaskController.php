<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\TaskCreated;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // fetch all tasks
        $tasks = Task::with('user')->get(['description', 'name']);
        $users = User::get(['id','name']);
        // load a view to display them
        return $tasks;
        // return view('tasks.index', compact('tasks','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $task = Task::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);
        event(new TaskCreated($task));
        return $task;
        // return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $task->user;
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
