<?php
setlocale(LC_ALL, 'fr_FR.UTF-8');

$title = 'Mes QCM';
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="header">{{ $title }}</h2>
    <hr>

    @if(count($qcms) === 0)
        <p class="alert alert-info">Vous n'avez créé aucun QCM pour l'instant.</p>
    @endif

    @foreach($qcms as $qcm)
        <div class="qcm">
            <h3 class="qcm__title">{{ $qcm->name }}</h3>
            <p class="qcm__metas">
                Créé le <time pudate="{{ $qcm->created_at }}">{{ $qcm->created_at->formatLocalized('%A %d %B %Y')}}</time>,
                dans &laquo; {{ $qcm->subject->name }} &raquo;.
            </p>
            <p>
                {{ $qcm->description }}
            </p>
            <div class="row">
                <div class="col-sm-4 text-right">
                    <a href="#" class="btn btn-default">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                        Consulter les notes
                    </a>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="{{ route('qcm::edit', ['id' => $qcm->id]) }}" class="btn btn-default">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        Modifier
                    </a>
                </div>
                <div class="col-sm-4 text-left">
                    <a href="{{ route('qcm::delete', ['id' => $qcm->id]) }}" class="btn btn-danger delete-qcm">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        Supprimer
                    </a>
                </div>
            </div>
        </div>
        <hr>
    @endforeach

    {!! $qcms->render() !!}
@endsection
