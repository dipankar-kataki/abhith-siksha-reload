<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KnowledgeForumPost extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="knowledge_forum_posts";
    protected $fillable=[ 'question', 'description', 'links', 'total_comments', 'total_views', 'user_id','user_detail_id' ,'is_activate', ];


    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    
    public function knowledgeForumComment(){
        return $this->hasMany('App\Models\KnowledgeForumComment');
    }

    public function userDetail(){
        return $this->belongsTo('App\Models\UserDetails', 'user_detail_id', 'id');
    }
}
