<?php
$title = 'Inscription';
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="page-header text-center">{{ $title }}</h2>

    <div class="row">
        <div class="col-md-6">
            {!! BootForm::openHorizontal($columnSizes)
        ->action(route('auth::register')) !!}

            <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>
            <br>

            {!! BootForm::text('Prénom' . $requiredField, 'first_name')
                ->required() !!}

            {!! BootForm::text('Nom de famille' . $requiredField, 'last_name')
                ->required() !!}

            {!! BootForm::email('Adresse e-mail' . $requiredField, 'email')
                ->required()
                ->placeholder('address@example.com') !!}

            {!! BootForm::select('Vous êtes' . $requiredField, 'status')
                ->required()
                ->options(['student' => 'Elève', 'teacher' => 'Enseignant']) !!}

            {!! BootForm::password('Mot de passe' . $requiredField, 'password')
                ->required() !!}

            {!! BootForm::password('Mot de passe (confirmation)' . $requiredField, 'password_confirmation')
                ->required() !!}

            {!! BootForm::submit("S'inscrire")
                ->class('btn btn-primary btn-kg') !!}

            {!! BootForm::close() !!}
        </div>
        <div class="col-md-6 text-center">
            <h3>Vous avez déjà un compte ?</h3>
            <p>
                <a href="{{ route('auth::login') }}" class="btn btn-primary">Se connecter</a>
            </p>
        </div>
    </div>
@endsection
