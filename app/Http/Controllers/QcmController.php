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

    }

    public function getCreate()
    {
        $subjectsList = Subject::toList();

        return view('qcm.create', compact('subjectsList'));
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

            foreach($datas['questions'] as $q => $question) {

                // Création des questions associées au QCM !!§
                $question = Question::create([
                    'qcm_id' => $qcm->id,
                    'question' => $question
                ]);

                foreach($datas['answers'][$q] as $a => $answer) {

                    // Création des réponses associées aux questions !§§
                    $answer = Answer::create([
                        'question_id' => $question->id,
                        'answer' => $answer,
                        'isValid' => (int) $datas['valids_answers'][$q] === $a
                    ]);
                }
            }

            return $qcm;
        });

        if($qcm != null) {
            Session::push('messages', 'success|Votre QCM a bien été créé');
            return redirect()->route('qcm::mine');
        } else {
            Session::push('messages', "danger|Le QCm n'a pas été créé");
            return redirect(URL::previous());
        }
    }
}
