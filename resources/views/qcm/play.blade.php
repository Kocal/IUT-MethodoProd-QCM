<?php
$title = $qcm->name;

$mustache = new Mustache_Engine();
BootForm::open();

$questions = $qcm->questions->count();
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="page-header text-center">{{ $title }}</h2>

    {!! BootForm::open()->route('qcm::play', ['id' => $qcm->id]) !!}

    <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub
                style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>

    <p>
        <b>Matière associée&nbsp;:</b>&nbsp;{{ $qcm->subject->name }}<br>
        <b>Créé le&nbsp;:</b>&nbsp;{{ $qcm->created_at() }}<br>
        <b>Professeur&nbsp;:</b>&nbsp;{{ $qcm->user->names() }}
    </p>

    <div id="questions-container">
        <br>
        {{-- On récupère le template "Mustache" des questions --}}
        <?php ob_start(); ?>
        @include('partials.mustache_template_question_for_view')
        <?php $template = ob_get_clean(); ?>

        {{-- BLADE & MUSTACHE AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA --}}
        @for($question = 0; $question < $questions; $question++)
            <?php
            $q = $qcm->questions->get($question);

            foreach($q->answers as $k => $answer) {
                $answer->index = $k;
            }
            ?>
            <div class="question" data-question="{{ $question }}">
                {!! $mustache->render($template, [
                    'questionNumber' => $question,
                    'questionNumberDisplay' => $question + 1,
                    'question' => $q->question,
                    'answers' => $q->answers,
                ]) !!}
            </div>
        @endfor
    </div>

    <hr>

    <div class="text-center">
        {!! BootForm::submit('Valider mes réponses', 'btn-create-qcm') !!}
    </div>

    {!! BootForm::close() !!}

@endsection
