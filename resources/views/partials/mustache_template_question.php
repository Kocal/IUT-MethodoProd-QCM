
<fieldset>
    <legend>Question n°<span
            class="questionNumberDisplay">{{ questionNumberDisplay }} <?= $requiredField; ?></span>
            <span title="Supprimer la question n°{{ questionNumberDisplay }}"
                  class="btn-remove-question glyphicon-remove"></span>
    </legend>
    <?= BootForm::textarea('Énoncé' . $requiredField, 'questions[{{questionNumber}}]')->rows(2)->required()->value('{{question}}'); ?>

    <table class="table-qcm table-responsive">
        <thead>
        <tr>
            <th class="text-center">Bonne réponse <?= $requiredField; ?></th>
            <th>Réponses <?= $requiredField; ?></th>
        </tr>
        </thead>
        <tbody>
        {{#answers}}
            <tr>
                <td class="table-qcm__valid">

                    {{^isValid}}
                        <?= $radio = BootForm::radio('', 'valids_answers[{{questionNumber}}]', '{{index}}')->required()->uncheck(); ?>
                    {{/isValid}}

                    {{#isValid}}
                        <?= $radio = BootForm::radio('', 'valids_answers[{{questionNumber}}]', '{{index}}')->required()->check(); ?>
                    {{/isValid}}

                </td>
                <td class="table-qcm__answer">
                    <?= BootForm::text('', 'answers[{{questionNumber}}][]')->hideLabel()->required()->value('{{answer}}'); ?>
                </td>
            </tr>
            {{/answers}}
        </tbody>
    </table>
</fieldset>
