<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartOrOrderAssignSubject extends Model
{
    use SoftDeletes;
    protected $table="cart_or_order_assign_subjects";
    protected $fillable=[
        'cart_id',
        'order_id',
        'assign_subject_id',
        'amount',
        'type',
        'addons_id'
    ];
    public function subject(){
        return $this->belongsTo(AssignSubject::class,'assign_subject_id','id');
    }
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
