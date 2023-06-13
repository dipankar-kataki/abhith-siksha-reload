<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'report_posts';
    protected $fillable = ['knowledge_forum_post_id','report_count','report_reason','is_activate'];

    protected $casts = [
        'report_reason' => 'array'
    ];


    public function knowledgeForumPost(){
        return $this->belongsTo('App\Models\KnowledgeForumPost');
    }
}
