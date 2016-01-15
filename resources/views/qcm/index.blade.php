<?php
use Illuminate\Support\Str;
$title = "Liste des QCM";
?>

@extends('layouts.default')
@section('title', $title)

@section('content')

    <h2 class="page-header text-center">{{ $title }}</h2>

    @if(count($qcms) == 0)
        <div class="alert alert-info">Aucun QCM n'a été créé pour le moment.</div>
    @else
        @foreach($qcms as $qcm)
            <div class="qcm">
                <h3 class="qcm__title">{{ $qcm->name }}</h3>
                <p class="qcm__metas">
                    Créé le <time pudate="{{ $qcm->created_at }}">{{ ucfirst($qcm->created_at()) }}</time>,
                    dans &laquo;&nbsp;<span title="{{ $qcm->subject->name }}">{{ Str::words($qcm->subject->name, 3) }}</span>&nbsp;&raquo;,
                    par {{ $qcm->user->names() }}
                </p>
                <p>{{ $qcm->description }}</p>
                <p>
                    @if(Auth::user()->played($qcm))
                        <a href="{{ route('qcm::play', ['id' => $qcm->id]) }}" class="btn btn-primary" disabled>Vous avez déjà participé</a>
                    @else
                        <a href="{{ route('qcm::play', ['id' => $qcm->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Voir</a>
                    @endif
                </p>
            </div>
            <hr>
        @endforeach
    @endif
@endsection
