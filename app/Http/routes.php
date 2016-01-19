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


/**
 * Gestion pour le QCM
 */
Route::group(['as' => 'qcm::', 'prefix' => 'qcm'], function() {
    // Affiche tous les QCM
    Route::get('/', ['as' => 'index', 'uses' => 'QcmController@index']);

    // Routes réservées aux utilisateurs connectés
    Route::group(['middleware' => 'student'], function() {
        // Affiche le QCM #id
        Route::get('/play/{id}', ['as' => 'play', 'uses' => 'QcmController@getPlay'])
            ->where('id', '[0-9]+');

        // Enregistre la participation au QCM #id
        Route::post('/play/{id}', ['as' => 'play', 'uses' => 'QcmController@postPlay'])
            ->where('id', '[0-9]+');

        Route::get('/results', ['as' => 'results', 'uses' => 'QcmController@getResultsOfStudent']);
    });

    // Routes réservées aux professeurs
    Route::group(['middleware' => 'teacher'], function() {

        // Affiche les QCM créés par le professeur
        Route::get('mine', ['as' => 'mine', 'uses' => 'QcmController@getMine']);

        // Affiche les notes des étudiants sur un QCM
        Route::get('notes/{id}', ['as' => 'notes', 'uses' => 'QcmController@getNotes'])
            ->where('id', '[0-9]+');

        // Affiche le formulaire de création de QCM
        Route::get('create', ['as' => 'create', 'uses' => 'QcmController@getCreate']);

        // Traitement pour la création du QCM
        Route::post('create', ['as' => 'create', 'uses' => 'QcmController@postCreate']);

        // Affiche le formulaire d'édition de QCM
        Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'QcmController@getEdit'])
            ->where('id', '[0-9]+');

        // Traitement pour l'édition du QCM
        Route::post('edit/{id}', ['as' => 'edit', 'uses' => 'QcmController@postEdit'])
            ->where('id', '[0-9]+');

        Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'QcmController@delete'])
            ->where('id', '[0-9]+');
    });
});
