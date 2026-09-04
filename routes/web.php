<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Page d'accueil
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/inscription/client', [RegisterController::class, 'showClientForm'])->name('register.client');
    Route::get('/inscription/livreur', [RegisterController::class, 'showLivreurForm'])->name('register.livreur');
    Route::post('/inscription', [RegisterController::class, 'register'])->name('register');

    Route::get('/connexion', [LoginController::class, 'showForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
});

Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Espace CLIENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/tableau-de-bord', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/livreurs-en-ligne', [ClientController::class, 'livreursEnLigne'])->name('livreurs.json');
    Route::get('/carte', [ClientController::class, 'carte'])->name('carte');

    Route::get('/commandes/creer', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/commandes', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Espace LIVREUR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('livreur.')->group(function () {
    Route::get('/tableau-de-bord', [LivreurController::class, 'dashboard'])->name('dashboard');
    Route::post('/commandes/{order}/accepter', [LivreurController::class, 'accepter'])->name('orders.accepter');
    Route::post('/commandes/{order}/refuser', [LivreurController::class, 'refuser'])->name('orders.refuser');
    Route::post('/commandes/{order}/statut', [LivreurController::class, 'changerStatut'])->name('orders.statut');
    Route::post('/position', [LivreurController::class, 'mettreAJourPosition'])->name('position.update');
    Route::post('/en-ligne', [LivreurController::class, 'toggleEnLigne'])->name('toggle.online');
});

/*
|--------------------------------------------------------------------------
| Messagerie (accessible client + livreur, vérifié dans le contrôleur)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:client,livreur,admin'])->prefix('commandes')->name('orders.')->group(function () {
    Route::get('/{order}/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/{order}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/{order}/messages/json', [MessageController::class, 'fetch'])->name('messages.fetch');
});

/*
|--------------------------------------------------------------------------
| Espace ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tableau-de-bord', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
    Route::delete('/utilisateurs/{user}', [AdminController::class, 'supprimerUtilisateur'])->name('utilisateurs.supprimer');
    Route::get('/commandes', [AdminController::class, 'commandes'])->name('commandes');
});
