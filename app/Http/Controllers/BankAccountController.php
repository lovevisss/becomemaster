<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bank-accounts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('bank-accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $data['number'] = preg_replace('/\s+/', '', $data['number']);

        $companyId = $data['company_id'] ?? null;
        $isPrimary = !empty($data['is_primary']);

        if (!empty($companyId)) {
            $hasExistingAccount = BankAccount::where('company_id', $companyId)->exists();
            $data['is_primary'] = $isPrimary || !$hasExistingAccount;

            if ($data['is_primary']) {
                BankAccount::where('company_id', $companyId)->update(['is_primary' => false]);
            }
        }

        $bankAccount = BankAccount::create($data);

        if (!empty($bankAccount->company_id)) {
            return redirect()->route('companies.show', $bankAccount->company_id)
                ->with('success', 'Bank Account created successfully.');
        }

        return redirect()->route('projects.index')
            ->with('success', 'Bank Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccount $bankAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BankAccount $bankAccount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccount $bankAccount)
    {
        //
    }
}
