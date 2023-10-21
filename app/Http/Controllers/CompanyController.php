<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

use App;

class CompanyController extends Controller
{

    // user need to login to access this controller
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $companies = Company::all();

        $companies = Company::where('user_id', auth()->user()->id)->get();
   
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
        ]);

        // with message success
        session()->flash('message', 'Company created successfully');
        return redirect()->route('companies.show', $company->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
        $company = Company::where('id', $company->id)->with('projects')->first();

        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $company->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        // with message success
        // session()->flash('message', 'Company updated successfully');

        return redirect()->route('companies.show', $company->id)->with('message', 'Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('message', 'Company deleted successfully');
    }

    public function buy(Request $request)
    {
        try{
            $billing = App::make('App\Acme\Billing\BillingInterface');
            $customerid = $billing->charge(
                [
                    'amount' => 100,
                    'token' => $request->input('stripe-token'),
                    'description' => $request->input('email'),
                ]
            );
    
            $user = auth()->user();
            $user->billing_id = $customerid;
            $user->save();
    
            return 'Charged successfully';
        }catch(Exception $e){
            return Redirect::refresh()->withFlashMessage($e->getMessage());
        }
        // dd($request->all());
       
    }
}
