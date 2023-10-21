<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // need to login before
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::where('user_id', auth()->user()->id)->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'days' => $request->days,
            'company_id' => $request->company_id,
            'user_id' => auth()->user()->id,
        ]);

        // with message success
        session()->flash('message', 'Project created successfully');
        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project = Project::where('id', $project->id)->with('tasks')->first();
        $comments = $project->comments;
        return view('projects.show', compact('project', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $projectUpdate = Project::where('id', $project->id)->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($projectUpdate) {
            session()->flash('message', 'Project updated successfully');
        }

        return redirect()->route('projects.show', $project->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        session()->flash('message', 'Project deleted successfully');
    }
}
