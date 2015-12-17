<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';

    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Valide les données issues du formulaire d'inscription
     *
     * @param array $datas
     * @return \Illuminate\Validation\Validator
     */
    public function validateRegistration(array $datas) {
        return Validator::make($datas, [
            'first_name' => 'required',
            'last_name'  => 'required',
            'email'      => 'required|email|max:176|unique:users',
            'status'     => 'required|in:student,teacher',
            'password'   => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);
    }

    /**
     * Valide les données issues du formulaire de connexion
     *
     * @param array $datas
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validateAuthentification(array $datas) {
        return Validator::make($datas, [
            'email'    => 'required|email|max:176',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Créer un nouvel utilisateur et renvoie une instance de User
     *
     * @param array $datas
     * @return \App\User
     */
    public function createUser(array $datas) {
        return User::create([
            'first_name' => $datas['first_name'],
            'last_name'  => $datas['last_name'],
            'status'     => $datas['status'],
            'email'      => $datas['email'],
            'password'   => bcrypt($datas['password']),
        ]);
    }

    /**
     * Action exécutée après la soumission du formulaire d'inscription par l'utilisateur
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postRegister(Request $request) {
        $datas = $request->all();
        $validator = $this->validateRegistration($datas);

        if($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        Auth::login($this->createUser($datas));
        Session::push('messages', "success|Votre inscription s'est correctement terminée");
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Affiche un message après une connexion réussie
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request) {
        Session::push('messages', 'success|Connexion réussie');
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Déconnecte l'utilisateur
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout(Request $request) {
        Session::push('messages', 'success|Vous avez bien été déconnecté');
        Auth::logout();
        return redirect('/');
    }
}
