<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeForumComment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="knowledge_forum_comments";
    protected $fillable = [ 'knowledge_forum_post_id', 'comments', 'user_id','user_detail_id', 'is_activate', ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function knowledgeForumPost(){
        return $this->belongsTo('App\Models\KnowledgeForumPost');
    }

    public function userDetailComment(){
        return $this->belongsTo('App\Models\UserDetails','user_detail_id','id');
    }
}
