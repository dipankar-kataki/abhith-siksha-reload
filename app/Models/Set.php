<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;

    protected $table = "sets";
    protected $guarded = [];
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function assignClass()
    {
        return $this->belongsTo(AssignClass::class);
    }
    public function assignTeacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
    public function assignSubject()
    {
        return $this->belongsTo(AssignSubject::class);
    }
    public function question(){
        return $this->hasMany(Question::class);
    }
    public function activequestion(){
        return $this->hasMany(Question::class)->where('is_activate',1);  
    }
}
