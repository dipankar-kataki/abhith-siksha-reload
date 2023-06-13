<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPracticeTestAnswer extends Model
{
    use SoftDeletes;
    protected $table = "user_practice_test_answers";
    protected $fillable = [
        'user_practice_test_id',
        'question_id',
        'answer',
        'user_answer',
        'is_correct'
    ];
    public function userPracticeTest(){
        return $this->belongsTo(userPracticeTest::class);
    }
    public function question(){
        return $this->hasOne(Question::class,'id','question_id');
    }
}
