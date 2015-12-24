<?php
    $title = "Création d'un nouveau QCM";

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

    {!! BootForm::open()->id('qcm-creator') !!}
    <p class="alert alert-info">Les champs marqué d'un astérisque &laquo;<sub style="font-size: 16px"><?= $requiredField ?></sub> &raquo; sont obligatoires.</p>

    {!! BootForm::text('Nom du QCM' . $requiredField, 'name')->required() !!}
        {!! BootForm::textarea('Description du QCM' . $requiredField, 'description')->rows(3)->required() !!}
        {!! BootForm::select('Matière associée' . $requiredField, 'subject_id')->options($subjectsList)->required() !!}
        <div id="questions-container"></div>

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
        <fieldset>
            <legend>Question n°<span class="questionNumberDisplay">@{{ questionNumberDisplay }} {!! $requiredField !!}</span>
                <span title="Supprimer la question n°@{{ questionNumberDisplay }}" class="btn-remove-question glyphicon-remove""></span>
            </legend>
            {!! BootForm::textarea('Énoncé' . $requiredField, 'questions[ @{{ questionNumber }} ]')->rows(2)->required() !!}

            <table class="table-qcm table-responsive">
                <thead>
                    <tr>
                        <th class="text-center">Bonne réponse {!! $requiredField !!}</th>
                        <th>Réponses {!! $requiredField !!}</th>
                    </tr>
                </thead>
                <tbody>
                    @{{ #answers }}
                        <tr>
                            <td class="table-qcm__valid">
                                {!! BootForm::radio('', 'valids_answers[@{{ questionNumber }}]', '@{{ . }}')->required() !!}
                            </td>
                            <td class="table-qcm__answer">
                                {!! BootForm::text('', 'answers[@{{ questionNumber }}][]')->hideLabel()->required() !!}
                            </td>
                        </tr>
                    @{{ /answers }}
                </tbody>
            </table>
        </fieldset>
    </script>

    <script>
        var qcm;

        (function($) {
            qcm = new QCM({
                answersNumberPerQuestion: 3
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
