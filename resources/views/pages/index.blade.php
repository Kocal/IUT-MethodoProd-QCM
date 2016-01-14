@extends('layouts.default')

@section('title', "Page d'accueil")

@section('content')

    <h2 class="page-header text-center">Bienvenue sur QCM.fr</h2>

    <div class="row">
        <div class="col-md-7">
            <a href="{{ asset('img/illustration.png') }}">
                <img src="{{ asset('img/illustration.png') }}" alt="Interface de création de QCM" style="max-width: 100%">
            </a>
        </div>
        <div class="col-md-5 text-justify">
            <p><b>QCM.fr</b> est un site fictif créé dans le cadre d'un TP dans le module
                &laquo;&nbsp;<i>Méthodologie de Production</i>&nbsp;&raquo;, à l'IUT Informatique.</p>
            <br>
            <p>Utilisant les technologies du développement web (HTML, CSS, PHP et JavaScript), mais aussi <b>Laravel</b>
                et <b>Bootstrap</b>, QCM.fr propose aux professeurs
                de créer des QCMs pour leurs élèves.</p>
            <br>

            <p><em>Note: la page d'inscription n'a été concue que parce que le site est fictif, dans la vraie vie,
                    le site utiliserait les données relatives aux professeurs/étudiants déjà stockées par l'école.</em></p>
            <br>
        </div>
    </div>

@endsection
