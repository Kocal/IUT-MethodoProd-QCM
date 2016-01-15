<?php
$title = "Vos résultats";
?>

@extends('layouts.default')@section('title', $title)

@section('content')

    <h2 class="page-header text-center">{{ $title }}</h2>

    @if(count($results) == 0)
        <div class="alert alert-info">Vous n'avez participé à aucun QCM pour l'instant</div>
    @else
        <table class="table table-qcm">
            <thead>
            <tr>
                <th>Nom</th>
                <th>Matière</th>
                <th>Points</th>
                <th>Taux de réussite</th>
            </tr>
            </thead>

            <tbody>
            @foreach($results as $result)
                <?php
                    $qcm = $result->qcm;
                    $validsAnswers = $result->getPoints();
                    $questionCount = $qcm->questions->count();
                ?>
                <tr>
                    <td>{{ $qcm->name }}</td>
                    <td>{{ $qcm->subject->name }}</td>
                    <td>{{ $validsAnswers }}&nbsp;/&nbsp;{{ $questionCount }}</td>
                    <td>{{ round($validsAnswers / (float) $questionCount * 100, 2) }}&nbsp;%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endsection
