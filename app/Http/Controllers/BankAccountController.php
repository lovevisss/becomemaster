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
        ]);

        $bankAccount = BankAccount::create($data);

        if (!empty($bankAccount->company_id)) {
            return redirect()->route('company.show', $bankAccount->company_id)
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
