<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $table = 'boards';
    protected $guarded = [];
     
    public function assignedClasses(){
        return $this->hasMany(AssignClass::class);
    }
}
