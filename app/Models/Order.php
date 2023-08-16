<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function course(){
        return $this->belongsTo('App\Models\Course');
    }
    public function chapter(){
        return $this->belongsTo('App\Models\Chapter');
    }
    public function board(){
        return $this->belongsTo('App\Models\Board');
    }

    public function assignClass(){
        return $this->belongsTo('App\Models\AssignClass');
    }
    public function assignSubject(){
        return $this->hasMany(CartOrOrderAssignSubject::class,'order_id','id');
    }

    public function selectedAddons(){
        return $this->hasMany('App\Models\SelectedAddon');
    }
}
