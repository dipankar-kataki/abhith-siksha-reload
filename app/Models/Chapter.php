<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'chapters';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    public function getCourse(){
        return $this->belongsTo(Course::class,'course_id','id');
    }

    public function cart(){
        if(Auth::check()){
            return $this->hasMany('App\Models\Cart','chapter_id','id')->where('user_id','=',Auth::user()->id)->where('is_paid','=',0)->where('is_remove_from_cart','=',0);
        }else{
            return $this->hasMany('App\Models\Cart','chapter_id','id');
        }
    }

    public function order(){
        return $this->hasMany('App\Models\Order');
    }
}
