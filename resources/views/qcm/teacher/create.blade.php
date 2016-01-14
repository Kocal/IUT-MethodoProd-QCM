<?php
$title = "Création d'un nouveau QCM";

$mustache = new Mustache_Engine();
BootForm::open();

$answers = 3; // 3 réponses par questions
$questions = 3; // 3 questions affichées par défaut
?>

@extends('layouts.default')

@section('title', $title)

@section('content')
    <h2 class="page-header text-center">{{ $title }}</h2>

    {!! BootForm::open()->id('qcm-creator') !!}
    <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub
                style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>

    {!! BootForm::text('Nom du QCM' . $requiredField, 'name')->required() !!}
    {!! BootForm::textarea('Description du QCM' . $requiredField, 'description')->rows(3)->required() !!}
    {!! BootForm::select('Matière associée' . $requiredField, 'subject_id')->options($subjectsList)->required() !!}

    <div id="questions-container">
        {{-- On récupère le template "Mustache" des questions --}}
        <?php ob_start(); ?>
        @include('partials.mustache_template_question')
        <?php $template = ob_get_clean(); ?>

        {{-- BLADE & MUSTACHE AAAAAAAAAAAAAAAAAAAAAAAAAAAAAA --}}
        @for($question = 0; $question < $questions; $question++)
            <?php
                $answersTab = [];

                for($i = 0; $i < $answers; $i++) {
                    $answersTab[$i] = [
                        'index' => $i
                    ];
                }
            ?>

            <div class="question" data-question="{{ $question }}">
            {!! $mustache->render($template, [
                'questionNumber' => $question,
                'questionNumberDisplay' => $question + 1,
                'answers' => $answersTab,
            ]) !!}
            </div>
        @endfor
    </div>

    <hr>

    <div class="text-center">
        <button type="button" class="btn-add-question">
            <span class="glyphicon glyphicon-plus-sign"></span> Ajouter une question
        </button>

        {!! BootForm::submit('Créer le QCM', 'btn-create-qcm') !!}
    </div>

    {!! BootForm::close() !!}
@endsection


@section('js')
    <script id="template-question" type="x-tmpl-mustache">
         {!! $template !!}
    </script>

    <script>
        var qcm;

        (function ($) {
            qcm = new QCM({
                answersNumberPerQuestion: 3,
                questionsNumber: 3
            }, {
                form: '#qcm-creator',
                container: '#questions-container',
                template: '#template-question',

                btnAddQuestion: '.btn-add-question',
                btnRemoveQuestion: '.btn-remove-question',
            });

            qcm.init();
        })(jQuery);
    </script>
@endsection
