<?php
$title = 'Connexion';

$columnSizes = [
    'sm' => [4, 8],
    'lg' => [2, 10]
];
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
        <h2 class="header">{{ $title }}</h2>
        <hr>

        {!! BootForm::openHorizontal($columnSizes)
            ->action(route('auth::login')) !!}

            {!! BootForm::text('Adresse e-mail', 'email')
                ->required()
                ->placeholder('adresse@exemple.com') !!}

            {!! BootForm::password('Mot de passe', 'password')
                ->required() !!}

            {!! BootForm::submit('Se connecter')
                ->class('btn btn-primary') !!}

        {!! BootForm::close() !!}
@endsection
