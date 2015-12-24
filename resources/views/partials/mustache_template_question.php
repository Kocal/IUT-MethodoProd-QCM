
<fieldset>
    <legend>Question n°<span
            class="questionNumberDisplay">{{ questionNumberDisplay }} <?= $requiredField; ?></span>
            <span title="Supprimer la question n°{{ questionNumberDisplay }}"
                  class="btn-remove-question glyphicon-remove"></span>
    </legend>
    <?= BootForm::textarea('Énoncé' . $requiredField, 'questions[ {{questionNumber}} ]')->rows(2)->required(); ?>

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
                <?= BootForm::radio('', 'valids_answers[{{questionNumber}}]', '{{.}}')->required(); ?>
            </td>
            <td class="table-qcm__answer">
                <?= BootForm::text('', 'answers[@{{questionNumber}}][]')->hideLabel()->required(); ?>
            </td>
        </tr>
        {{/answers}}
        </tbody>
    </table>
</fieldset>