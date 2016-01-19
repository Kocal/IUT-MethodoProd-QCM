<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests;
use App\Participation;
use App\Qcm;
use App\Question;
use App\Subject;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Redirect;
use Session;
use URL;

class QcmController extends Controller
{

    public function index()
    {
        $qcms = Qcm::with('user', 'subject')->orderBy('created_at', 'desc')
                   ->paginate(20);

        return view('qcm.index', compact('qcms'));
    }

    public function getPlay(Request $request, $id)
    {
        $qcm = Qcm::with('user', 'subject')->findOrFail($id);

        if (Auth::user()->hasPlayed($qcm)) {
            Session::push(
                'messages',
                'danger|Vous ne pouvez pas participer deux fois à ce QCM'
            );

            return Redirect::route('qcm::index');
        }

        return view('qcm.play', compact('qcm'));
    }

    public function postPlay(Request $request, $id)
    {
        $qcm = Qcm::findOrFail($id);

        if (Auth::user()->hasPlayed($qcm)) {
            Session::push(
                'messages',
                'danger|Vous ne pouvez pas participer deux fois à ce QCM'
            );

            return Redirect::route('qcm::index');
        }

        $ret = DB::transaction(
            function () use ($request, $qcm) {

                $datas = $request->all();

                $questions          = $qcm->questions;
                $questionsCount     = $questions->count();
                $validsAnswersCount = count($datas[ 'valids_answers' ]);

                if ($questionsCount != $validsAnswersCount) {
                    Session::push(
                        'messages',
                        'danger|Le nombre de questions est différent du nombre de vos réponses'
                    );

                    return Redirect::to(URL::previous());
                }

                foreach ($questions as $k => $question) {
                    $validAnswer = $datas[ 'valids_answers' ][ $k ];
                    $answer      = $question->answers->get($validAnswer);

                    if ($answer == null) {
                        Session::push('messages', 'danger|???');

                        return Redirect::to(URL::previous());
                    }

                    Participation::create(
                        [
                            'user_id'     => Auth::id(), 'qcm_id' => $qcm->id,
                            'question_id' => $question->id,
                            'answer_id'   => $answer->id,
                        ]
                    );
                }

                return true;
            }
        );

        if ($ret) {
            Session::push(
                'messages',
                'success|Votre participation a bien été prise en compte'
            );

            return Redirect::route('qcm::index');
        } else {
            Session::push(
                'messages',
                'danger|Il y a eu un soucis lors de votre participation'
            );

            return Redirect::to(URL::previous());
        }
    }

    public function getResultsOfStudent(Request $request)
    {
        $results = [];
        $user    = User::where('id', Auth::id())->with(
            'participations',
            'participations.qcm',
            'participations.qcm.subject',
            'participations.qcm.questions',
            'participations.answer'
        )->first();

        $participations = $user->participations;
        $lastid         = 0;

        foreach ($participations as $participation) {

            $qcm = $participation->qcm;

            if ($lastid == $qcm->id) {
                continue;
            }

            $lastid = $qcm->id;

            $results[ $lastid ] = new class($participations, $qcm)
            {

                public $participations;
                public $qcm;

                public function __construct($participations, $qcm)
                {
                    $this->participations = $participations;
                    $this->qcm            = $qcm;
                }

                public function getPoints()
                {
                    $points         = 0;
                    $participations = $this->participations->where(
                        'qcm_id',
                        $this->qcm->id
                    );

                    foreach ($participations as $participation) {
                        $answer = $participation->answer;
                        $points += $answer->isValid;
                    }

                    return $points;
                }
            };
        }

        return view('qcm.results', compact('results'));
    }

    public function getNotes(Request $request, $id)
    {
        $qcm = Qcm::with(
            'subject',
            'participations',
            'participations.answer',
            'participations.user'
        )->findOrFail($id);

        $participations = $qcm->participations;
        $results        = [];

        foreach ($participations as $participation) {
            if (!isset($results[ $participation->user_id ])) {
                $results[ $participation->user_id ] = new class($participation->user)
                {
                    public $user;
                    public $points;

                    public function __construct($user)
                    {
                        $this->user   = $user;
                        $this->points = 0;
                    }
                };
            }

            $answer = $participation->answer;
            $results[ $participation->user_id ]->points += $answer->isValid;
        }

        return view('qcm.teacher.notes', compact('qcm', 'results'));
    }

    public function getCreate()
    {
        $subjectsList = Subject::toList();

        return view('qcm.teacher.create', compact('subjectsList'));
    }

    public function postCreate(Request $request)
    {
        $this->validate(
            $request,
            [
                'name'             => 'required|string',
                'description'      => 'required|string',
                'subject_id'       => 'required|in:'.implode(
                        ',',
                        array_keys(Subject::toList())
                    ), 'questions' => 'required|array',
                'valids_answers'   => 'required|array|size_array:questions|answer_exists:answers',
                'answers'          => 'required|array',
            ]
        );

        $qcm = DB::transaction(
            function () use ($request) {
                $datas = $request->all();

                // Création du QCM wow!!§
                $qcm = Qcm::create(
                    [
                        'user_id'     => Auth::id(),
                        'subject_id'  => $datas[ 'subject_id' ],
                        'name'        => $datas[ 'name' ],
                        'description' => $datas[ 'description' ],
                    ]
                );

                foreach ($datas[ 'questions' ] as $q => $question) {

                    // Création des questions associées au QCM !!§
                    $question = Question::create(
                        [
                            'qcm_id' => $qcm->id, 'question' => $question,
                        ]
                    );

                    foreach ($datas[ 'answers' ][ $q ] as $a => $answer) {

                        // Création des réponses associées aux questions !§§
                        $answer = Answer::create(
                            [
                                'question_id' => $question->id,
                                'answer'      => $answer,
                                'isValid'     => (int)$datas[ 'valids_answers' ][ $q ]
                                                 === $a,
                            ]
                        );
                    }
                }

                return $qcm;
            }
        );

        if ($qcm != null) {
            Session::push('messages', 'success|Votre QCM a bien été créé');

            return redirect()->route('qcm::mine');
        } else {
            Session::push('messages', "danger|Le QCM n'a pas été créé");

            return redirect(URL::previous());
        }
    }

    public function getEdit(Request $request, $id)
    {
        $qcm          = Qcm::where('id', $id)->with('user', 'questions')->first(
        );
        $subjectsList = Subject::toList();

        if (Auth::id() !== $qcm->user->id) {
            Session::push(
                'messages',
                "danger|Vous ne pouvez pas modifier un QCM qui n'est pas le votre"
            );

            return redirect()->route('qcm::mine');
        }

        return view('qcm.teacher.edit', compact('qcm', 'subjectsList'));
    }

    public function postEdit(Request $request, $id)
    {
        $qcm = Qcm::where('id', $id)->with('user', 'questions')->first();

        if (Auth::id() === $qcm->user->id) {

            $this->validate(
                $request,
                [
                    'name'             => 'required|string',
                    'description'      => 'required|string',
                    'subject_id'       => 'required|in:'.implode(
                            ',',
                            array_keys(Subject::toList())
                        ), 'questions' => 'required|array',
                    'valids_answers'   => 'required|array|size_array:questions|answer_exists:answers',
                    'answers'          => 'required|array',
                ]
            );

            $ret = DB::transaction(
                function () use ($request, $qcm) {
                    $datas     = $request->all();
                    $questions = $qcm->questions;

                    $qcm->name        = $datas[ 'name' ];
                    $qcm->description = $datas[ 'description' ];
                    $qcm->subject_id  = $datas[ 'subject_id' ];
                    $qcm->update();

                    foreach ($questions as $q => $question) {
                        $question->question = $datas[ 'questions' ][ $q ];
                        $question->update();

                        foreach ($question->answers as $a => $answer) {
                            $answer->answer  = $datas[ 'answers' ][ $q ][ $a ];
                            $answer->isValid = ($datas[ 'valids_answers' ][ $q ]
                                                == $a);
                            $answer->update();
                        }
                    }

                    return true;
                }
            );

            if ($ret) {
                Session::push('messages', 'success|Le QCM a bien été modifié');
            } else {
                Session::push('messages', "danger|Le QCM n'a pas été modifié");
            }

            return redirect(route('qcm::mine'));
        } else {
            Session::push(
                'messages',
                'danger|Vous ne pouvez pas modifier le QCM d\'un autre professeur'
            );
            Auth::logout();

            return redirect(route('index'));
        }
    }

    public function getMine()
    {
        $qcms = Qcm::where('user_id', Auth::id())->with('subject')->orderBy(
            'created_at',
            'desc'
        )->paginate(20);

        $qcms->setPath(route('qcm::mine'));

        return view('qcm.teacher.mine', compact('qcms'));
    }

    public function delete(Request $request, $id)
    {
        $qcm = Qcm::where('id', $id)->with('user', 'questions')->first();

        if (Auth::id() == $qcm->user->id) {

            $ret = DB::transaction(
                function () use ($qcm) {

                    foreach ($qcm->participations as $participation) {
                        $participation->delete();
                    }

                    foreach ($qcm->questions as $question) {

                        foreach ($question->answers as $answer) {
                            $answer->delete();
                        }

                        $question->delete();
                    }

                    $qcm->delete();

                    return true;
                }
            );

            if ($ret) {
                Session::push('messages', 'success|Le QCM a bien été supprimé');
            } else {
                Session::push('messages', "danger|Le QCM n'a pas été supprimé");
            }

            return redirect(route('qcm::mine'));

        } else {
            Session::push(
                'messages',
                'danger|Vous ne pouvez pas supprimer le QCM d\'un autre professeur'
            );
            Auth::logout();

            return redirect(route('index'));
        }
    }
}
