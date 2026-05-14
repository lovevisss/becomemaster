<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Events\OrderStatusUpdated;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/', function () {
//     // OrderStatusUpdated::dispatch(new Order(1));
//     return view('welcome');
// })->name('home');

Auth::login(User::find(1));

Route::get('/', function (){
    dd($_REQUEST['ticket']);
    $casServerHostUrl = CAS_SERVER_URL;
    $service = APP_SERVER_URL;
    $ticket = $_REQUEST['ticket'];
    $casUser = CasUtil::serviceValidate($casServerHostUrl, $service, $ticket);
    dd($casUser);
    return $casUser;
//    redirect()
    return 'index';
});

Route::get('/teststatus', function (){
    return view('auth.source.index');
});

Route::get('/testWord', [\App\Http\Controllers\WordController::class, 'getTestPage']);

Route::post('testWord', [\App\Http\Controllers\WordController::class, 'test'])->name('upload.file');

Route::get('/testWrite', [\App\Http\Controllers\WordController::class,'writeExcel']);

Route::resource('apply_forms', \App\Http\Controllers\ApplyFormController::class);
Route::resource('companies', \App\Http\Controllers\CompanyController::class)->names('companies');
Route::resource('bank_accounts', \App\Http\Controllers\BankAccountController::class);
Route::resource('contract', \App\Http\Controllers\ContractController::class);
//Route::post('contract/{contract}', [\App\Http\Controllers\ContractController::class, 'update']);

Route::resource('projects', \App\Http\Controllers\ProjectController::class);
Route::resource('contracts', \App\Http\Controllers\ContractController::class)->names('contracts');

Route::get('BigPayForm/{project}', [\App\Http\Controllers\ProjectController::class, 'getBigPayForm'])->name('projects.getBigPayForm');

Route::post('payment_invoice', [\App\Http\Controllers\PaymentController::class, 'updateInvoice']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/subscribe', function () {
    return view('subscribe');
})->middleware(['auth', 'verified'])->name('subscribe');



// Route::post('buy', [\App\Http\Controllers\CompanyController::class, 'buy'])->name('buy');

// Route::get('/product-checkout', function (Request $request) {
//     return $request->user()->checkout('price_tshirt');
// });









// Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');







//Route::resource('companies', \App\Http\Controllers\CompanyController::class);
Route::resource('roles', \App\Http\Controllers\RoleController::class);
Route::resource('users', \App\Http\Controllers\UserController::class);
Route::resource('user', \App\Http\Controllers\UserController::class);
Route::resource('funding', \App\Http\Controllers\FundingController::class);
Route::resource('bank_account', \App\Http\Controllers\BankAccountController::class);
Route::resource('project_type', \App\Http\Controllers\ProjectTypeController::class);
Route::resource('contract_type', \App\Http\Controllers\ContractTypeController::class);

Route::resource('notify_papers', \App\Http\Controllers\NotifyPaperController::class);

Route::get('apply_userlist', [\App\Http\Controllers\UserController::class, 'apply_userlist']);
Route::resource('tasks', \App\Http\Controllers\TaskController::class);
Route::resource('projects', \App\Http\Controllers\ProjectController::class);
Route::resource('comments', \App\Http\Controllers\CommentController::class);

Route::get('flex', function (){
    return view('flex');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




















require __DIR__.'/auth.php';

Route::resource('qrcodes', App\Http\Controllers\QrcodeController::class);
Route::resource('roles', App\Http\Controllers\RoleController::class);
Route::resource('users', App\Http\Controllers\UserController::class);
Route::resource('transactions', App\Http\Controllers\TransactionController::class);
