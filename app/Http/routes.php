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

Route::get('/', ['as' => 'index', 'uses' => 'PageController@pageIndex']);

/**
 * Gestion de l'authentification et inscription
 */
Route::group(['as' => 'auth::', 'prefix' => 'auth'], function() {
    // Affiche le formulaire d'authentification
    Route::get('login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLogin']);

    // Traitement pour l'authentification
    Route::post('login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLogin']);

    // Affiche le formulaire pour l'inscription
    Route::get('register', ['as' => 'register', 'uses' => 'Auth\AuthController@getRegister']);

    // Traitement pour l'inscription
    Route::post('register', ['as' => 'register', 'uses' => 'Auth\AuthController@postRegister']);

    // Déconnecte l'utilisateur
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogout']);
});
