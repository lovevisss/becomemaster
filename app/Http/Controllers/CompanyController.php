<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

use App;

class CompanyController extends Controller
{

    // user need to login to access this controller
    public function __construct()
    {
        $this->middleware('auth')->except('searchByName');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('keyword', ''));

        if ($request->wantsJson()) {
            return Company::query()
                ->select(['id', 'name'])
                ->orderBy('name')
                ->get();
        }

        $companies = Company::query()
            ->with('user')
            ->withCount(['projects', 'bankAccounts'])
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($innerQuery) use ($keyword) {
                    $innerQuery->where('name', 'like', "%{$keyword}%")
                        ->orWhere('tax_id', 'like', "%{$keyword}%")
                        ->orWhere('telephone', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%")
                        ->orWhereHas('user', function ($userQuery) use ($keyword) {
                            $userQuery->where('name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(12)
            ->appends($request->query());


//        $companies = Company::where('user_id', auth()->user()->id)->get();

        return view('companies.index', compact('companies', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('companies.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name'],
            'tax_id' => ['nullable', 'string', 'max:255', 'unique:companies,tax_id'],
            'address' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsible_user_name' => ['nullable', 'string', 'max:255'],
            'bank_accounts' => ['nullable', 'array'],
            'bank_accounts.*.name' => ['nullable', 'string', 'max:255'],
            'bank_accounts.*.number' => ['nullable', 'string', 'max:255'],
            'primary_bank_account_index' => ['nullable', 'integer', 'min:0'],
            'bank_name' => ['nullable', 'string', 'max:255', 'required_with:bank_number'],
            'bank_number' => ['nullable', 'string', 'max:255', 'required_with:bank_name'],
        ]);

        $company = Company::create([
            'name' => $data['name'],
            'tax_id' => $data['tax_id'] ?? null,
            'address' => $data['address'] ?? null,
            'telephone' => $data['telephone'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $bankAccounts = collect($data['bank_accounts'] ?? [])
            ->map(function ($account) {
                return [
                    'name' => trim((string) ($account['name'] ?? '')),
                    'number' => preg_replace('/\s+/', '', (string) ($account['number'] ?? '')),
                ];
            })
            ->filter(function ($account) {
                return $account['name'] !== '' && $account['number'] !== '';
            })
            ->values();

        // Backward-compatible fallback for old single-bank-account submission.
        if ($bankAccounts->isEmpty() && !empty($data['bank_name']) && !empty($data['bank_number'])) {
            $bankAccounts = collect([
                [
                    'name' => trim((string) $data['bank_name']),
                    'number' => preg_replace('/\s+/', '', (string) $data['bank_number']),
                ],
            ]);
        }

        if ($bankAccounts->isNotEmpty()) {
            $primaryIndex = $data['primary_bank_account_index'] ?? 0;

            $bankAccounts->each(function ($account, $index) use ($company, $primaryIndex) {
                $company->bankAccounts()->create([
                    'name' => $account['name'],
                    'number' => $account['number'],
                    'is_primary' => ((int) $index) === ((int) $primaryIndex),
                ]);
            });

            if (!$company->bankAccounts()->where('is_primary', true)->exists()) {
                $company->bankAccounts()->oldest('id')->limit(1)->update(['is_primary' => true]);
            }
        }

        $responsibleUser = $this->resolveResponsibleUser($data);
        if ($responsibleUser) {
            $responsibleUser->update(['company_id' => $company->id]);
        }

        // with message success
        session()->flash('message', 'Company created successfully');
        return redirect()->route('companies.show', $company->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        $company = Company::where('id', $company->id)
            ->with(['projects', 'bankAccounts' => function ($query) {
                $query->orderByDesc('is_primary')->orderBy('id');
            }])
            ->first();

        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $users = User::all();
        return view('companies.edit', compact('company', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:companies,name,' . $company->id],
            'tax_id' => ['nullable', 'string', 'max:255', 'unique:companies,tax_id,' . $company->id],
            'address' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'responsible_user_name' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update([
            'name' => $data['name'],
            'tax_id' => $data['tax_id'] ?? null,
            'address' => $data['address'] ?? null,
            'telephone' => $data['telephone'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        // Keep one responsible user per company by clearing the previous binding first.
        User::where('company_id', $company->id)->update(['company_id' => null]);
        $responsibleUser = $this->resolveResponsibleUser($data);
        if ($responsibleUser) {
            $responsibleUser->update(['company_id' => $company->id]);
        }

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

    public function searchByName(Request $request)
    {
        $name = $request->input('name');

        $companies = Company::where('name', 'like', '%'.$name.'%')->get();
        return $this->getJsonResponse($companies);

    }

    public function getJsonResponse($model)
    {
        $res = response()->json($model);
        $res->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        return $res;
    }

    private function resolveResponsibleUser(array $data): ?User
    {
        if (!empty($data['user_id'])) {
            return User::find($data['user_id']);
        }

        $responsibleUserName = trim((string) ($data['responsible_user_name'] ?? ''));
        if ($responsibleUserName === '') {
            return null;
        }

        $existingUser = User::where('name', $responsibleUserName)->orderBy('id')->first();
        if ($existingUser) {
            return $existingUser;
        }

        return User::create([
            'name' => $responsibleUserName,
            'role_id' => 4,
        ]);
    }
}
