<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{

    public $timestamps = false;

    protected $fillable = ['user_id', 'question_id', 'answer_id'];
}
