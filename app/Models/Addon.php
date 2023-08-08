<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $table = 'addons';
    protected $guarded = [];

    public function assignSubject(){
        return $this->belongsTo(AssignSubject::class, 'subject_id', 'id');
    }
}
