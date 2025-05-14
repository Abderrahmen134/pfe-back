<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LigneDevisController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TypeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('clients', ClientController::class);
Route::apiResource('products', ProductController::class);
Route::resource('devis', DevisController::class)->only([
    'index', 'store', 'update', 'destroy'
]);

Route::apiResource('ligne-devis', LigneDevisController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
Route::middleware('api.token')->group(function () {
    Route::get('/client/profile', function (Request $request) {
            return $request->auth_client; // retournera le client authentifié
     });
    
        // ou toute autre route protégée
    });
    
});
Route::get('/ligne-devis/devis/{id}', [App\Http\Controllers\LigneDevisController::class, 'getByDevis']);
Route::get('/devis/client/{id}', [DevisController::class, 'getDevisByClient']);
Route::apiResource('admins', App\Http\Controllers\AdminController::class);
Route::put('/devis/{id}/demander-commande', [DevisController::class, 'demanderCommande']);
Route::put('/devis/{id}/gererCommande', [DevisController::class, 'gererCommande']);
Route::get('/devis/CommandesDemandees', [DevisController::class, 'devisCommandesDemandees']);
Route::get('/devis/commandeValidee', [DevisController::class, 'commandeValidee']);
Route::post('/admin/register', [AdminController::class, 'register']);
Route::post('/admin/login', [AdminController::class, 'login']);
Route::resource('admin', AdminController::class)->only([
    'index', 'store', 'update', 'destroy'
]);
Route::get('/devis/commandeLivree', [DevisController::class, 'commandeLivree']);
Route::patch('/clients/{id}/statut', [ClientController::class, 'updateStatut']);
Route::apiResource('types', TypeController::class);



