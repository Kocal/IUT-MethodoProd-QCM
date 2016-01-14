<?php
$title = 'Connexion';
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="page-header text-center">{{ $title }}</h2>

    <div class="row">
        <div class="col-md-6">
            {!! BootForm::openHorizontal($columnSizes)
        ->action(route('auth::login')) !!}

            <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub
                        style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>

            <br>

            {!! BootForm::text('Adresse e-mail' . $requiredField, 'email')
                ->required()
                ->placeholder('adresse@exemple.fr') !!}

            {!! BootForm::password('Mot de passe' . $requiredField, 'password')
                ->required() !!}

            {!! BootForm::submit('Se connecter')
                ->class('btn btn-primary') !!}

            {!! BootForm::close() !!}
        </div>
        <div class="col-md-6 text-center">
            <h3>Pas encore de compte sur QCM.fr ?</h3>
            <p>Inscrivez-vous, c'est simple et rapide !</p>
            <p>
                <a href="{{ route('auth::register') }}" class="btn btn-primary">S'inscrire</a>
            </p>
        </div>
    </div>
@endsection
