<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignClass extends Model
{
    use HasFactory;

    protected $table = "assign_classes";

    protected $guarded = [];


    public function boards(){
        return $this->belongsTo(Board::class, 'board_id', 'id');
    }

    public function subjects(){
        return $this->hasMany(AssignSubject::class);
    }
}
