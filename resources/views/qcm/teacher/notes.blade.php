<?php
$titleDocument = 'Résultats de ' . $qcm->name;
$titlePage = 'Résultats de &laquo;&nbsp;' . $qcm->name . '&nbsp;&raquo;';
?>

@extends('layouts.default')

@section('title', $titleDocument)

@section('content')

    <h2 class="page-header text-center">{{ $titlePage }}</h2>

    <p>
        <b>Matière associée&nbsp;:</b>&nbsp;{{ $qcm->subject->name }}<br>
        <b>Créé le&nbsp;:</b>&nbsp;{{ $qcm->created_at() }}<br>
    </p>

    <hr>

    @if(count($results) === 0)
        <div class="alert alert-info">Aucun étudiant n'a encore participé à ce QCM</div>
    @else
        <?php
            $questionCount = $qcm->questions->count();
        ?>
        <table class="table table-qcm sortable" data-order='[[ 3, "desc" ]]' data-page-length='25'>
            <thead>
            <tr>
                <th>#</th>
                <th>Étudiant</th>
                <th>Note</th>
                <th>% réussite</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $k => $result)
                <tr>
                    <td>{{ $k }}</td>
                    <td>{{ $result->user->names() }}</td>
                    <td>{{ $result->points }}&nbsp;/&nbsp;{{ $questionCount }}</td>
                    <td>{{ round($result->points / (float) $questionCount * 100, 2) }}&nbsp;%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/bs/dt-1.10.10,r-2.0.0/datatables.min.css"/>
@endsection

@section('js')
    <script type="text/javascript" src="https://cdn.datatables.net/s/bs/dt-1.10.10,r-2.0.0/datatables.min.js"></script>
    <script>
        (function($) {
            $('table').DataTable({
                language: {
                    processing:     "Traitement en cours...",
                    search:         "Rechercher&nbsp;:&nbsp;",
                    lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                    info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                    infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                    infoFiltered:   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                    infoPostFix:    "",
                    loadingRecords: "Chargement en cours...",
                    zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    emptyTable:     "Aucune donnée disponible dans le tableau",
                    paginate: {
                        first:      "Premier",
                        previous:   "Pr&eacute;c&eacute;dent",
                        next:       "Suivant",
                        last:       "Dernier"
                    },
                    aria: {
                        sortAscending:  ": activer pour trier la colonne par ordre croissant",
                        sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    }
                }
            });
        })(jQuery);
    </script>
@endsection
