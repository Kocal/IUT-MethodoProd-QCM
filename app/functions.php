<?php
/**
 * Formate les alertes stockées en Session pour qu'elles s'affichent correctement avec Bootstrap.
 * Supprime ensuite ces alertes de la Session.
 *
 * @return string Concaténation des alertes
 */
function displayAlert() {
    $ret = '';

    if(Session::has('messages')) {
        $ret .= '<div class="alert-container">';

        foreach(Session::get('messages') as $k => $message) {
            list($type, $message) = explode('|', $message);
            $ret .= sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
        }

        $ret .= '</div>';
    }

    Session::forget('messages');

    return $ret;
}