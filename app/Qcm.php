<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qcm extends Model
{
    protected $fillable = ['user_id', 'subject_id', 'name', 'description'];
}
