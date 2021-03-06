<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;

    protected $fillable = ['qcm_id', 'question'];

    public function answers() {
        return $this->hasMany('\App\Answer');
    }
}
