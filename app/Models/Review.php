<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $table="reviews";
    protected $fillable=['user_id','subject_id','review','rating','is_visible'];
    public function subject(){
        return $this->belongsTo(AssignSubject::class,'subject_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    

}
