<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Funding;
use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ContractController extends Controller
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
        $companies = Company::all();
        $users = User::all();
        return view('contracts.create',compact('companies', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->hasFile('path')) {
            return response()->json(['message' => '请上传合同文件。'], 422);
        }

        $file = $request->file('path');
        if (!$file || !$file->isValid()) {
            return response()->json(['message' => '合同文件无效或上传失败，请重新上传。'], 422);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer'],
            'code' => ['nullable', 'string', 'max:255'],
            'signing_date' => ['required', 'date'],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $request->input('company_id'));
                }),
            ],
            'amount' => ['required', 'numeric'],
            'fulfillmentDeposit' => ['nullable', 'numeric'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'funding_id' => ['nullable', 'integer', 'exists:fundings,id'],
            'project_type_id' => ['nullable', 'integer', 'exists:project_types,id'],
            'contract_type_id' => ['nullable', 'integer', 'exists:contract_types,id'],
            'path' => ['required', 'file'],
        ]);


        $path = $file->store('public/contracts');
        $path = str_replace('public/', '', $path);

        $contract = Contract::create(array_diff_key($validated, ['path' => true]));
        $contract->path = $path;
        $contract->save();
        return $contract;
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $users = User::all();
        $companies = Company::all();
        return view('contracts.edit', compact('contract','users', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
//        如果有文件 则更新
//        dd($request);
//        dd('update');
        $contract->fill($request->all());
        $file = $request->file('path');
        if($file){
            $path = $file->store('public/contracts');
            $path = str_replace('public/', '', $path);
            $contract->path = $path;

        }
        $consult = $request->file('consult');
        if($consult){
//            dd($consult);
            $consultPath = $consult->store('public/contracts');
            $consultPath = str_replace('public/', '', $consultPath);
            $contract->consult = $consultPath;
        }

        $acceptance = $request->file('acceptance');
        if($acceptance){
            $acceptancePath = $acceptance->store('public/contracts');
            $acceptancePath = str_replace('public/', '', $acceptancePath);
            $contract->acceptance = $acceptancePath;
        }
        $contract->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
