<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherAssignToSubject extends Model
{
    use SoftDeletes;
    protected $table = "teacher_assign_to_subjects";
    protected $fillable = [
        'id',
        'user_id',
        'assign_subject_id',
        'status'
    ];
    public function subject(){
        return $this->belongsTo(AssignSubject::class,'assign_subject_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
