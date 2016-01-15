<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests;
use App\Qcm;
use App\Question;
use App\Subject;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class QcmController extends Controller
{

    public function index()
    {
        $qcms = Qcm::with('user', 'subject')->orderBy('created_at', 'desc')->paginate(30);

        return view('qcm.index', compact('qcms'));
    }

    public function getPlay(Request $request, $id) {
        $qcm = Qcm::with('user', 'subject')->findOrFail($id);

        return view('qcm.view', compact('qcm'));
    }

    public function postPlay(Request $request, $id) {
        $qcm = Qcm::findOrFail($id);

    }

    public function getCreate()
    {
        $subjectsList = Subject::toList();

        return view('qcm.teacher.create', compact('subjectsList'));
    }

    public function postCreate(Request $request)
    {
        $this->validate($request, [
            'name'           => 'required|string',
            'description'    => 'required|string',
            'subject_id'     => 'required|in:' . implode(',', array_keys(Subject::toList())),
            'questions'      => 'required|array',
            'valids_answers' => 'required|array|size_array:questions|answer_exists:answers',
            'answers'        => 'required|array',
        ]);

        $qcm = DB::transaction(function () use ($request) {
            $datas = $request->all();

            // Création du QCM wow!!§
            $qcm = Qcm::create([
                'user_id'     => Auth::id(),
                'subject_id'  => $datas['subject_id'],
                'name'        => $datas['name'],
                'description' => $datas['description'],
            ]);

            foreach ($datas['questions'] as $q => $question) {

                // Création des questions associées au QCM !!§
                $question = Question::create([
                    'qcm_id'   => $qcm->id,
                    'question' => $question,
                ]);

                foreach ($datas['answers'][ $q ] as $a => $answer) {

                    // Création des réponses associées aux questions !§§
                    $answer = Answer::create([
                        'question_id' => $question->id,
                        'answer'      => $answer,
                        'isValid'     => (int)$datas['valids_answers'][ $q ] === $a,
                    ]);
                }
            }

            return $qcm;
        });

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
        $qcm = Qcm::where('id', $id)->with('user', 'questions')->first();
        $subjectsList = Subject::toList();

        if (Auth::id() !== $qcm->user->id) {
            Session::push('messages', "danger|Vous ne pouvez pas modifier un QCM qui n'est pas le votre");

            return redirect()->route('qcm::mine');
        }

        return view('qcm.teacher.edit', compact('qcm', 'subjectsList'));
    }

    public function postEdit(Request $request, $id)
    {
        $qcm = Qcm::where('id', $id)->with('user', 'questions')->first();

        if (Auth::id() === $qcm->user->id) {

            $this->validate($request, [
                'name'           => 'required|string',
                'description'    => 'required|string',
                'subject_id'     => 'required|in:' . implode(',', array_keys(Subject::toList())),
                'questions'      => 'required|array',
                'valids_answers' => 'required|array|size_array:questions|answer_exists:answers',
                'answers'        => 'required|array',
            ]);

            $ret = DB::transaction(function () use ($request, $qcm) {
                $datas = $request->all();
                $questions = $qcm->questions;

                $qcm->name = $datas['name'];
                $qcm->description = $datas['description'];
                $qcm->subject_id = $datas['subject_id'];
                $qcm->update();

                foreach ($questions as $q => $question) {
                    $question->question = $datas['questions'][ $q ];
                    $question->update();

                    foreach ($question->answers as $a => $answer) {
                        $answer->answer = $datas['answers'][ $q ][ $a ];
                        $answer->isValid = ($datas['valids_answers'][ $q ] == $a);
                        $answer->update();
                    }
                }

                return true;
            });

            if ($ret) {
                Session::push('messages', 'success|Le QCM a bien été modifié');
            } else {
                Session::push('messages', "danger|Le QCM n'a pas été modifié");
            }

            return redirect(route('qcm::mine'));
        } else {
            Session::push('messages', 'danger|Vous ne pouvez pas modifier le QCM d\'un autre professeur');
            Auth::logout();

            return redirect(route('index'));
        }
    }

    public function getMine()
    {
        $qcms = Qcm::where('user_id', Auth::id())
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $qcms->setPath(route('qcm::mine'));

        return view('qcm.teacher.mine', compact('qcms'));
    }

    public function delete(Request $request, $id)
    {
        $qcm = Qcm::where('id', $id)->with('user', 'questions')->first();

        if (Auth::id() == $qcm->user->id) {

            $ret = DB::transaction(function () use ($qcm) {

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
            });

            if ($ret) {
                Session::push('messages', 'success|Le QCM a bien été supprimé');
            } else {
                Session::push('messages', "danger|Le QCM n'a pas été supprimé");
            }

            return redirect(route('qcm::mine'));

        } else {
            Session::push('messages', 'danger|Vous ne pouvez pas supprimer le QCM d\'un autre professeur');
            Auth::logout();

            return redirect(route('index'));
        }
    }
}
