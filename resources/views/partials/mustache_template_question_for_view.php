
<fieldset>
    <legend>Question n°<span
            class="questionNumberDisplay">{{ questionNumberDisplay }}</span>
    </legend>

    <p>{{ question }}</p>

    <table class="table-qcm table-qcm-view table-responsive">
        <thead>
        <tr>
            <th class="text-center">Bonne réponse<?= $requiredField; ?></th>
            <th>Réponses</th>
        </tr>
        </thead>
        <tbody>
        {{#answers}}
            <tr>
                <td class="table-qcm__valid">
                    <?= $radio = BootForm::radio('', 'valids_answers[{{questionNumber}}]', '{{index}}')->required()->uncheck(); ?>
                </td>
                <td class="table-qcm__answer">
                    {{ answer }}
                </td>
            </tr>
            {{/answers}}
        </tbody>
    </table>
</fieldset>
