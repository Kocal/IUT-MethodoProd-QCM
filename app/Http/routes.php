<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', function () {
    die('Oui');
});

/**
 * Gestion des utilisateurs
 */
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);
// Traitement pour la connexion
Route::post('auth/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);
// Déconnexion
Route::get('auth/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
// Affiche le formulaire d'inscription
Route::get('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);
// Traitement pour l'inscription
Route::post('auth/register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);