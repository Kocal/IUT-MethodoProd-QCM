<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Session;

class Teacher {

    /**
     * @var \Illuminate\Auth\Guard
     */
    protected $auth;

    /**
     * Teacher constructor.
     *
     * @param \Illuminate\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($this->auth->check() && $this->auth->user()['status'] === 'teacher') {
            return $next($request);
        }

        Session::push('messages', 'danger|Vous devez être professeur pour accéder à cette page');
        return redirect('/');
    }
}
