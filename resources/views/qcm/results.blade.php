<?php
$title = "Vos résultats";
?>

@extends('layouts.default')

@section('title', $title)

@section('content')

    <h2 class="page-header text-center">{{ $title }}</h2>

    @if(count($results) == 0)
        <div class="alert alert-info">Vous n'avez participé à aucun QCM pour l'instant</div>
    @else
        <table class="table table-qcm sortable" data-order='[[ 4, "desc" ]]' data-page-length='25'>
            <thead>
            <tr>
                <th>#</th>
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
                    <td>{{ $qcm->id }}</td>
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
                        search:         "Rechercher&nbsp;:",
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
