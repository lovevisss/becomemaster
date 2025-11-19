<?php

namespace App\Http\Controllers;

use App\Models\NotifyPaper;
use Illuminate\Http\Request;

class NotifyPaperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifyPapers = NotifyPaper::all();
        return view('notify_papers.index', compact('notifyPapers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notify_papers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file = $request->file('path');


        $path = $file->store('public/notify_papers');
//      $path  去掉public/
        $path = str_replace('public/', '', $path);
        $notifyPaper = NotifyPaper::create($request->all());
        $notifyPaper->path = $path;
        $notifyPaper->save();
        return $notifyPaper;
    }

    /**
     * Display the specified resource.
     */
    public function show(NotifyPaper $notifyPaper)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NotifyPaper $notifyPaper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NotifyPaper $notifyPaper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NotifyPaper $notifyPaper)
    {
        //
    }
}
