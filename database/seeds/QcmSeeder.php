<?php

use App\Qcm;
use App\Subject;
use App\Question;
use App\Answer;
use Faker\Factory;
use Illuminate\Database\Seeder;

class QcmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('fr_FR');
        $subjectsCount = Subject::all()->count();

        for($qcms = 0; $qcms < 100; $qcms++) {
            $qcm = Qcm::create([
                'user_id'     => 1,
                'subject_id'  => rand(1, $subjectsCount - 1),
                'name'        => $faker->sentence(6),
                'description' => $faker->sentence(40)
            ]);

            for($questions = 0; $questions < rand(5, 10); $questions++) {

                $question = Question::create([
                    'qcm_id' => $qcm->id,
                    'question' => $faker->sentence(20)
                ]);

                for($answers = 0; $answers < rand(2, 4); $answers++) {

                    Answer::create([
                        'question_id' => $question->id,
                        'answer'      => $faker->sentence(10),
                        'isValid'     => $answers == 0,
                    ]);
                }
            }
        }
    }
}
