<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{

    public $timestamps = false;

    protected $fillable = ['user_id', 'qcm_id', 'question_id', 'answer_id'];

    public function qcm() {
        return $this->belongsTo('\App\Qcm');
    }

    public function user() {
        return $this->belongsTo('\App\User');
    }

    public function answers() {
        return $this->hasMany('\App\Answer', 'id');
    }

    public function answer() {
        return $this->hasOne('\App\Answer', 'id', 'answer_id');
    }
}
