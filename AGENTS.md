# AGENTS Guide

## Project Snapshot
- Laravel 10 monolith (PHP 8.1+) with Blade + Vue (Vite) frontend, MySQL primary DB, Redis cache/queue, Pusher broadcasting.
- Codebase mixes InfyOm-generated CRUD (`AppBaseController` + `Repository`) and hand-written controllers/models.
- Root route redirects to `projects` (`routes/web.php`), so contract/project workflows are the operational center.

## Fast Local Workflows
- Install deps: `composer install` and `npm install`.
- Run app: `php artisan serve` + `npm run dev` (or Sail: `./vendor/bin/sail up -d`).
- Typical setup after fresh DB: `php artisan migrate --seed` (seeders in `database/seeders/DatabaseSeeder.php` create lookup tables used by forms).
- Tests use in-memory SQLite (`phpunit.xml`), run with `php artisan test`.
- Broadcast auth routes are enabled via `App\Providers\BroadcastServiceProvider` (`Broadcast::routes()`).

## Architecture and Data Flows
- **Contract/project listing flow:** `ProjectController@index` queries `Contract` with eager loads (`contract_type`, `project_type`, `company`, `status`) and drives `resources/views/projects/index.blade.php`.
- **DOCX import flow:** `WordController@test` parses uploaded `.docx` tables, then calls model-level `CreateOrUpdate` methods (`Company`, `User`, `Project`, `Contract`, `Payment`, `BankAccount`, `Funding`) using `App\Acme\Parser` key extraction.
- **Realtime task flow:** task creation in `TaskController@store` and `routes/api.php` emits `TaskCreated`; frontend listens on private channel `tasks.{projectId}` in `resources/js/components/TaskList.vue`.
- **View composition:** `AppServiceProvider::boot` globally injects `fundings`, `contract_types`, `project_types` into `contracts.create` and `contracts.edit`.

## Project-Specific Conventions
- Many models use `protected $guarded = []`; do not mass-assign unvalidated request data unless controller validation exists.
- Non-standard method naming exists: several models expose `CreateOrUpdate` (capitalized) and are relied on by controllers.
- `Company` keeps a one-responsible-user pattern by writing `users.company_id` (see `CompanyController@update`).
- Bank accounts are multi-row per company; primary account is tracked by `bank_accounts.is_primary` (`CompanyController@store`).
- Some routes/resources are duplicated in `routes/web.php` (`projects`, `roles`, `users` declared multiple times); check route conflicts before adding new endpoints.

## Integration Points
- Broadcasting: `config/broadcasting.php`, `routes/channels.php`, `resources/js/bootstrap.js`, `TaskCreated` event.
- Payments/billing: custom binding `BillingInterface -> StripeBilling` in `BillingServiceProvider`; also Laravel Cashier + Passport/Sanctum are installed in dependencies.
- Document generation/parsing: `phpoffice/phpword` used in `WordController` and `ProjectController@getBigPayForm` with templates under `public/excel/`.
- File uploads store under `storage/app/public` (e.g., contracts, invoices); controllers often strip `public/` when persisting paths.

## When Editing Safely
- Prefer following existing style in the touched module (repository-based CRUD vs direct Eloquent) instead of refactoring across patterns.
- For Word import changes, update parser keys and corresponding model `CreateOrUpdate` logic together, then verify relationships saved in `WordController::processData`.
- For realtime changes, keep channel names and auth callbacks aligned across `TaskCreated`, `routes/channels.php`, and Vue Echo subscriptions.
- If you add fields used in filters/forms, update both controller query logic and Blade filter inputs (example: project/company filters in `projects.index`).

