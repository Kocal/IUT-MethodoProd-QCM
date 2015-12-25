<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('size_array', function($attribute, $value, array $parameters, $validator) {
            $datas = $validator->getData();

            if(!isset($datas[$parameters[0]])) {
                $validator->errors()->add('undefined_index', "Le champ passé en paramètre n'existe pas");
                return false;
            }

            if(count($value) != count($datas[$parameters[0]])) {
                $validator->errors()->add('different_size', 'Les tableaux ont une taille différente');
                return false;
            }

            return true;
        });

        Validator::extend('answer_exists', function($attribute, $validAnswers, array $parameters, $validator) {
            $datas = $validator->getData();

            if(!isset($parameters[0]) || isset($parameters[0]) && !isset($datas[$parameters[0]])) {
                $validator->errors()->add('undefined', "Le champ passé en paramètre n'existe pas");
                return false;
            }

            $answers = $datas[$parameters[0]];

            if(!is_array($answers)) {
                $validator->errors()->add('array');
                return false;
            }

            if(!is_array($validAnswers)) {
                $validator->errors()->add('array');
                return false;
            }

            foreach($validAnswers as $k => $validAnswer) {

                if(!isset($answers[$k])) {
                    return false;
                }

                if(!isset($answers[$k][(int) $validAnswer])) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
