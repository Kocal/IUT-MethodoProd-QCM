<?php
require_once app_path('functions.php');

$user = Auth::user();
?>

@if (trim($__env->yieldContent('title')))
    @section('title') | QCM.fr @append
@else
    @section('title', 'QCM.fr')
@endif

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100' rel='stylesheet' type='text/css'>
        <link type="text/css" rel="stylesheet" href="{{ asset(elixir('css/all.css')) }}"  media="screen, projection"/>
        @section('css')
        @show
    </head>
    <body id="page">
        <header id="page__header">
            <nav role="navigation">
                <div class="container">
                    <div>
                        <h1 id="logo"><a href="{{ route('index') }}">QCM.fr</a></h1>
                    </div>
                    <div id="navigation__container">
                        <ul id="navigation__menu">
                            @if(Auth::check())
                                <?php switch($user['status']) {
                                    case 'student': { ?>
                                        <li><h4><a href="#">Participer</a></h4></li>
                                        <li><h4><a href="#">Résultats</a></h4></li>
                                    <?php break; }

                                    case 'teacher': { ?>
                                        <li><h4><a href="{{ route('qcm::mine') }}">Voir mes QCM</a></h4></li>
                                        <li><h4><a href="{{ route('qcm::create') }}">Créer un QCM</a></h4></li>
                                    <?php break; }

                                    default:
                                } ?>
                            @endif
                        </ul>
                        <ul id="user__menu">
                            @if(Auth::check())
                                <?php $status = trans('messages.' . $user['status']); ?>
                                <li>
                                    <b>{{ $user['first_name'] }} {{ $user['last_name'] }}
                                        (<span title="{{ $status }}">{{ str_limit($status, 4, '.') }}</span>)
                                    </b><br>
                                    <small><a href="{{ route('auth::logout') }}" class="">Se déconnecter</a></small>
                                </li>
                            @else
                                <li><a href="{{ route('auth::login') }}">Se connecter</a></li>
                                <li><a href="{{ route('auth::register') }}">S'inscrire</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <main id="page__content">
            <div class="container">
                <?= displayAlert() ?>
                @yield('content')
            </div>
        </main>

        <footer id="page__footer">
            <div class="container">
                <p><span class="copyleft">&copy;</span> 2015 - <b>L'équipe du Oui</b></p>
            </div>
        </footer>

        <script src="{{ asset(elixir('js/all.js')) }}"></script>
        @section('js')
        @show
    </body>
</html>
