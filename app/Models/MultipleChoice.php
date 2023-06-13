<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChoice extends Model
{
    use HasFactory;

    protected $table = 'multiple_choices';
    protected $guarded = [];


    public function subject(){
        return $this->belongsTo('App\Models\Subject');
    }
}
