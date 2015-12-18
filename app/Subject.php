<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Subject extends Model {

    protected $fillable = ['slug', 'name'];

    /**
     * Retourne une liste contenant les Matières sous la forme [$id] => $name
     *
     * @return array
     */
    public static function toList() {
        $list = [];
        $subjects = DB::table('subjects')->orderBy('name')->get(['id', 'name']);

        foreach($subjects as $subject) {
            $list[$subject->id] = $subject->name;
        }

        return $list;
    }

}
