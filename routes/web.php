<?php

use App\Http\Controllers\HospitalController;
use App\Http\Controllers\EmbassieesController;
use App\Http\Controllers\AirportsController;
use App\Http\Controllers\AircharterController;
use App\Http\Controllers\MasterairportController;
use App\Http\Controllers\MasterhospitalController;
use App\Http\Controllers\MasterembessyController;
use App\Http\Controllers\MasteraircharterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

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

Route::get('/', function (Request $request) {
    $token = $request->query('token');

    // Jika ada token, proses login otomatis
    if ($token) {
        try {
            $secret = env('JWT_AUTH_SECRET_KEY', 'Chelsea123!@#');
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            // Validasi token
            if ($decoded->iss !== 'https://id.concordreview.com') {
                return response('Issuer tidak valid', 403);
            }

            if ($decoded->exp < time()) {
                return response('Token kadaluarsa', 403);
            }

            $userData = (array) $decoded->data->user ?? null;

            if (!$userData || !isset($userData['email'])) {
                return response('Data user tidak ditemukan dalam token', 403);
            }

           $user = User::where('email', $userData['email'])->first();

           if (!$user) {
                return response('Token tidak valid (user tidak ditemukan)', 403);
            }

            Auth::login($user);
            return redirect('/home');

        } catch (\Exception $e) {
            return response('Token tidak valid: ' . $e->getMessage(), 403);
        }
    }

    // Jika tidak ada token, tampilkan halaman login biasa
    return view('pages.auth.login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('hospital', HospitalController::class);
    // Route::get('/api/hospitals', [HospitalController::class, 'api']);
    Route::get('/hospitals/{id}', [HospitalController::class, 'showdetail']);
    Route::get('/hospitals/clinic/{id}', [HospitalController::class, 'showdetailclinic']);
    Route::get('/hospitals/emergency/{id}', [HospitalController::class, 'showdetailemergency']);
    Route::resource('embassiees', EmbassieesController::class);
    // Route::get('/api/embassiees', [EmbassieesController::class, 'api']);
    Route::get('/embassiees/{id}/detail', [EmbassieesController::class, 'showdetail']);
    Route::resource('airports', AirportsController::class);
    // Route::get('/api/airports', [AirportsController::class, 'api']);
    Route::get('/airports/{id}/detail', [AirportsController::class, 'showdetail']);
    Route::get('/airports/{id}/emergency', [AirportsController::class, 'showdetailemergency']);
    Route::resource('aircharter', AircharterController::class);
    Route::get('/airports/{id}/airlinesdestination', [AirportsController::class, 'showairlinesdestination']);
    Route::get('/airports/{id}/navigation', [AirportsController::class, 'shownavigation']);
    Route::get('/api/airports', [AirportsController::class, 'filter']);
    Route::get('/api/embassy', [EmbassieesController::class, 'filter']);
    Route::get('/api/hospital', [HospitalController::class, 'filter']);
    Route::post('/user/{user}/update-role', [UserController::class, 'updateRole'])->name('user.updateRole');

    Route::resource('airportdata', MasterairportController::class);
    Route::resource('hospitaldata', MasterhospitalController::class);
    Route::resource('embessydata', MasterembessyController::class);
    Route::resource('aircharterdata', MasteraircharterController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('user', UserController::class);

    Route::post('/airportdata/{id}/toggle-status', [MasterairportController::class, 'toggleStatus'])->name('airportdata.toggleStatus');
    Route::post('/hospitaldata/{id}/toggle-status', [MasterhospitalController::class, 'toggleStatus'])->name('hospitaldata.toggleStatus');
    Route::post('/embassydata/{id}/toggle-status', [MasterembessyController::class, 'toggleStatus'])->name('embassydata.toggleStatus');

    Route::get('/administrator', [App\Http\Controllers\HomeController::class, 'administrator']);

    Route::get('/get-cities/{province_id}', [App\Http\Controllers\MasterembessyController::class, 'getCities']);


});

Route::middleware(['web', 'jwt.login'])->group(function () {
    Route::get('/airports', [AirportsController::class, 'index']);
    Route::get('/hospital', [HospitalController::class, 'index']);
    Route::get('/embassiees', [EmbassieesController::class, 'index']);
});
