<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'carts';
    protected $fillable = ['user_id','board_id','assign_class_id','chapter_id','course_id','is_full_course_selected','is_paid', 'is_remove_from_cart','is_buy'];

    public function chapter(){
        return $this->belongsTo('App\Models\Chapter');
    }

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }
    public function board(){
        return $this->belongsTo('App\Models\Board');
    }

    public function assignClass(){
        return $this->belongsTo('App\Models\AssignClass');
    }
    public function assignSubject(){
        return $this->hasMany(CartOrOrderAssignSubject::class,'cart_id','id')->where('type',1);
    }
}
