<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;

    protected $table = 'time_tables';
   
    protected $guarded = [];

    public function assignClass(){
        return $this->belongsTo(AssignClass::class, 'assign_class_id', 'id');
    }

    public function board(){
        return $this->belongsTo(Board::class, 'board_id', 'id');
    }
    public function assignSubject(){
        return $this->belongsTo(AssignSubject::class, 'assign_subject_id', 'id');
    }
    
}
