<?php

namespace App\Http\Controllers;

use App\Subject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class QcmController extends Controller {

    public function index() {

    }

    public function getCreate() {
        $subjectsList = Subject::toList();

        return view('qcm.create', compact('subjectsList'));
    }

    public function postCreate() {

        die('TODO: implémenter QcmController@postCreate');

    }

}
