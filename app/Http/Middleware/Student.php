<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Session;

class Student
{
    /**
     * @var \Illuminate\Auth\Guard
     */
    protected $auth;

    /**
     * Teacher constructor.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()
            && $this->auth->user()[ 'status' ] === 'student'
        ) {
            return $next($request);
        }

        Session::push(
            'messages',
            'danger|Vous devez être étudiant pour accéder à cette page'
        );

        return redirect('/');
    }
}
