<?php
$title = "Modification d'un QCM";

$mustache = new Mustache_Engine();
BootForm::open();

$questions = $qcm->questions->count();
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="page-header text-center">{{ $title }}</h2>

    {!! BootForm::open()!!}

    <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub
                style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>

    {!! BootForm::text('Nom du QCM' . $requiredField, 'name')->required()->value($qcm->name) !!}
    {!! BootForm::textarea('Description du QCM' . $requiredField, 'description')->rows(3)->required()->value($qcm->description) !!}
    {!! BootForm::select('Matière associée' . $requiredField, 'subject_id')->options($subjectsList)->required()->select($qcm->subject_id) !!}

    <div id="questions-container">
        {{-- On récupère le template "Mustache" des questions --}}
        <?php ob_start(); ?>
        @include('partials.mustache_template_question')
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
        {!! BootForm::submit('Modifier le QCM', 'btn-create-qcm') !!}
    </div>

    {!! BootForm::close() !!}

@endsection
