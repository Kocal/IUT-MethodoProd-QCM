<?php
$title = 'Inscription';

$columnSizes = [
    'sm' => [4, 8],
    'lg' => [3, 9]
];

$requiredField = ' <sup style="color: #f00">*</sup>';
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="header">{{ $title }}</h2>
    <hr>

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
@endsection
