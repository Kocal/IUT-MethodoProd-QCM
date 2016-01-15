<?php

namespace App;

use Auth;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'status', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function names()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function participations() {
        return $this->hasMany('\App\Participation');
    }

    public function hasPlayed(Qcm $qcm)
    {
        $participations = $this->participations();

        return $participations->count() != 0;
    }

    public function getPlayedQcms() {
        $qcms = [];

        foreach($this->hasMany('\App\Participation')->groupBy('qcm_id')->get() as $participation) {
            $qcms[] = $participation->qcm;
        }

        return $qcms;
    }
}
