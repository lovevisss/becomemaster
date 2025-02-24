<?php

use App\Http\Controllers\ProfileController;
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

Route::get('/', function (){
    return 'index';
});

Route::get('/testWord', [\App\Http\Controllers\WordController::class, 'getTestPage']);

Route::post('testWord', [\App\Http\Controllers\WordController::class, 'test'])->name('upload.file');

Route::get('/testWrite', [\App\Http\Controllers\WordController::class,'writeExcel']);

Route::resource('apply_forms', \App\Http\Controllers\ApplyFormController::class);
Route::resource('companies', \App\Http\Controllers\CompanyController::class);
Route::resource('contracts', \App\Http\Controllers\ContractController::class);

Route::resource('projects', \App\Http\Controllers\ProjectController::class);

Route::get('BigPayForm/{project}', [\App\Http\Controllers\ProjectController::class, 'getBigPayForm'])->name('projects.getBigPayForm');

Route::post('payment_invoice', [\App\Http\Controllers\PaymentController::class, 'updateInvoice']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/subscribe', function () {
    return view('subscribe');
})->middleware(['auth', 'verified'])->name('subscribe');

// // App::bind('App\Acme\Billing\BillingInterface', 'App\Acme\Billing\StripeBilling');


// Route::post('buy', [\App\Http\Controllers\CompanyController::class, 'buy'])->name('buy');

// Route::get('/product-checkout', function (Request $request) {
//     return $request->user()->checkout('price_tshirt');
// });

// Route::get('/redirect', function(){
//     $query = http_build_query([
//         'client_id' => '4',
//         'redirect_uri' => 'http://10.1.12.143/callback',
//         'response_type' => 'code',
//         'scope' => '',
//     ]);
//     return redirect('http://10.1.12.164/oauth/authorize?'.$query);
// });

// Route::get('/callback', function(Request $request){
//     $http = new GuzzleHttp\Client;
//     $response = $http->post('http://10.1.12.164/oauth/token', [
//         'form_params' => [
//             'grant_type' => 'authorization_code',
//             'client_id' => '4',
//             'client_secret' => 'BUybCfSMtLObrCEktxC6HVH8TMZd7mFwqkwUVteJ',
//             'redirect_uri' => 'http://10.1.12.143/callback',
//             'code' => $request->code,
//         ],
//     ]);
//     return json_decode((string) $response->getBody(), true);
// });





// Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');







Route::resource('companies', \App\Http\Controllers\CompanyController::class);
Route::resource('roles', \App\Http\Controllers\RoleController::class);
Route::resource('users', \App\Http\Controllers\UserController::class);
Route::get('apply_userlist', [\App\Http\Controllers\UserController::class, 'apply_userlist']);
Route::resource('tasks', \App\Http\Controllers\TaskController::class);
Route::resource('projects', \App\Http\Controllers\ProjectController::class);
Route::resource('comments', \App\Http\Controllers\CommentController::class);

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
