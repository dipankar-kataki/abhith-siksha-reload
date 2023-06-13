<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportBlog extends Model
{
    use HasFactory;

    protected $table = 'report_blogs';
    protected $fillable = ['blogs_id','report_count', 'report_reason', 'is_activate'];

    protected $casts = [
        'report_reason' => 'array'
    ];
    public function blogs(){
        return $this->belongsTo('App\Models\Blog');
    }
}
