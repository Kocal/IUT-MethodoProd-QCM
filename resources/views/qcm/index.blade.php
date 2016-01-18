<?php
use Illuminate\Support\Str;

$title = "Liste des QCM";
$check = Auth::check();
$user  = Auth::user();
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
                    Créé le
                    <time pudate="{{ $qcm->created_at }}">{{ ucfirst($qcm->created_at()) }}</time>
                    ,
                    {{ !!$qcm->subject }}
                    dans &laquo;&nbsp;<span title="{{ $qcm->subject->name }}">
                        {{ Str::words($qcm->subject->name, 3) }}</span>&nbsp;&raquo;,
                    par {{ $qcm->user->names() }}
                </p>
                <p>{{ Str::words($qcm->description, 30, '...') }}</p>
                <p>
                    @if($check && $user->isCreator($qcm))
                        <a href="{{ route('qcm::edit', ['id' => $qcm->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;Voir dans
                            l'interface d'édition</a>
                    @elseif($check && $user->hasPlayed($qcm))
                        <a href="{{ route('qcm::play', ['id' => $qcm->id]) }}" class="btn btn-primary" disabled>
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;Vous avez déjà
                            participé</a>
                    @else
                        <a href="{{ route('qcm::play', ['id' => $qcm->id]) }}" class="btn btn-primary">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>&nbsp;Voir</a>
                    @endif
                </p>
            </div>
            <hr>
        @endforeach
    @endif

    <div class="text-center">
        {!! $qcms->render()  !!}
    </div>
@endsection
