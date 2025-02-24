<?php

namespace App\Http\Controllers;

use App\Models\ApplyForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplyFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
//        dd($request);
//        store file


        $applyForm = new ApplyForm();
        $applyForm->project_id = $request->project_id;
        $applyForm->path = $request->file('file')->store('apply_form', 'public');
        $applyForm->user_id = $request->user_id;
        $applyForm->reason = $request->reason;
        $applyForm->save();
//        $applyForm->address = $request->address;
        return 'success';
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplyForm $applyForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplyForm $applyForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ApplyForm $applyForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplyForm $applyForm)
    {
        //
    }
}
