<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPracticeTest extends Model
{
    use SoftDeletes;
    protected $table = "user_practice_tests";
    protected $fillable = [
        'user_id',
        'set_id',
        'start_time',
        'end_time',
        'total_correct_count',
        'total_attempts',
        'total_duration',
        'total_active_question'
    ];
    public function userPracticeTestAnswer(){
        return $this->hasMany(UserPracticeTestAnswer::class,'user_practice_test_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function correctAnswer(){
        return $this->hasMany(UserPracticeTestAnswer::class,'user_practice_test_id','id')->where('is_correct',1);
    }
    public function incorrectAnswer(){
        return $this->hasMany(UserPracticeTestAnswer::class,'user_practice_test_id','id')->where('is_correct',0);
    }
    public function set(){
        return $this->belongsTo(Set::class, 'set_id', 'id');
    }
}
