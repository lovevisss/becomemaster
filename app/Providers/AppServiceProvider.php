<?php

namespace App\Providers;

use App\Models\ContractType;
use App\Models\Funding;
use App\Models\ProjectType;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['contracts.create', 'contracts.edit'], function ($view) {
            $fundings = Funding::all();
            $contract_types = ContractType::all();
            $project_types = ProjectType::all();
            $view->with('fundings', $fundings);
            $view->with('contract_types', $contract_types);
            $view->with('project_types', $project_types);
        });

    }
}
