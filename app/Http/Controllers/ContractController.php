<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Funding;
use App\Models\ProjectType;
use App\Models\User;
use App\Services\BigPayFormExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
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
        $contract->load(['company', 'user', 'status', 'project']);
        $users = User::all();
        $companies = Company::all();
        return view('contracts.edit', compact('contract','users', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer'],
            'code' => ['nullable', 'string', 'max:255'],
            'signing_date' => ['required', 'date'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $request->input('company_id'));
                }),
            ],
            'amount' => ['required', 'numeric'],
            'fulfillmentDeposit' => ['nullable', 'numeric'],
            'funding_id' => ['nullable', 'integer', 'exists:fundings,id'],
            'project_type_id' => ['nullable', 'integer', 'exists:project_types,id'],
            'contract_type_id' => ['nullable', 'integer', 'exists:contract_types,id'],
            'status_id' => ['nullable', 'integer', 'exists:statuses,id'],
            'path' => ['nullable', 'file'],
            'consult' => ['nullable', 'file'],
            'acceptance' => ['nullable', 'file'],
            'invoice' => ['nullable', 'file'],
        ]);

        $contract->fill(Arr::except($validated, ['path', 'consult', 'acceptance', 'invoice']));
        $file = $request->file('path');
        if($file){
            if (!$file->isValid()) {
                return response()->json(['message' => '合同文件无效或上传失败，请重新上传。'], 422);
            }

            $path = $file->store('public/contracts');
            $path = str_replace('public/', '', $path);
            $contract->path = $path;

        }
        $consult = $request->file('consult');
        if($consult){
            if (!$consult->isValid()) {
                return response()->json(['message' => '询价纪要文件无效或上传失败，请重新上传。'], 422);
            }

            $consultPath = $consult->store('public/contracts');
            $consultPath = str_replace('public/', '', $consultPath);
            $contract->consult = $consultPath;
        }

        $acceptance = $request->file('acceptance');
        if($acceptance){
            if (!$acceptance->isValid()) {
                return response()->json(['message' => '验收文件无效或上传失败，请重新上传。'], 422);
            }

            $acceptancePath = $acceptance->store('public/contracts');
            $acceptancePath = str_replace('public/', '', $acceptancePath);
            $contract->acceptance = $acceptancePath;
        }

        $invoice = $request->file('invoice');
        if($invoice){
            if (!$invoice->isValid()) {
                return response()->json(['message' => '发票文件无效或上传失败，请重新上传。'], 422);
            }

            $invoicePath = $invoice->store('public/contracts');
            $invoicePath = str_replace('public/', '', $invoicePath);
            $contract->invoice = $invoicePath;
        }

        $contract->save();

        return response()->json($contract->fresh(['company', 'user', 'status']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }

    public function exportBigPayForm(Contract $contract, BigPayFormExporter $exporter)
    {
        $contract->loadMissing([
            'project.company.user',
            'company.user',
            'company.bankAccounts',
            'funding',
        ]);

        $result = $exporter->export($contract->project, $contract);

        return response()
            ->download($result['path'], $result['download_name'], [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }
}
