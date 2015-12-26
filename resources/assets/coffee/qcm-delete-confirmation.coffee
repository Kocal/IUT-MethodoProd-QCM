(($) ->
    $(document).on 'click', '.delete-qcm', (event) ->
        $title = $(this).parents('.qcm').find '.qcm__title'

        confirm('Êtes-vous sûr de vouloir supprimer le QCM « ' + $.trim $title.text() + ' » ?')
) jQuery
