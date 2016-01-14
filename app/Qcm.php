<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Qcm extends Model
{
    protected $fillable = ['user_id', 'subject_id', 'name', 'description'];

    public function subject() {
        return $this->belongsTo('\App\Subject');
    }

    public function user() {
        return $this->belongsTo('\App\User');
    }

    public function questions() {
        return $this->hasMany('\App\Question');
    }

    public function participations() {
        // participations -> question -> qcm
        return $this->hasManyThrough('\App\Participation', '\App\Question');
    }

    public function created_at() {
        return $this->created_at->formatLocalized('%A %d %B %Y');
    }

}
