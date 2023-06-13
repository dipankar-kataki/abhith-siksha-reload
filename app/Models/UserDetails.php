<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    protected $table = 'user_details';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'education',
        'gender',
        'dob',
        'total_experience_year',
        'image',
        'user_id',
        'total_experience_month',
        'assign_board_id',
        'assign_class_id',
        'assign_subject_id',
        'hslc_percentage',
        'hs_percentage',
        'current_organization',
        'current_designation',
        'current_ctc',
        'resume_url',
        'teacherdemovideo_url',
        'address',
        'status',
        'referral_id',
        'assign_board_id',
        'assign_class_id',
        'parent_name',
        'is_above_eighteen'
    ];




    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function forumPost()
    {
        return $this->hasMany('App\Models\KnowledgeForumPost');
    }
    public function board()
    {
        return $this->belongsTo(Board::class,'assign_board_id','id');
    }
    public function assignClass()
    {
        return $this->belongsTo(AssignClass::class, 'assign_class_id', 'id');
    }
    public function assignSubject()
    {
        return $this->belongsTo(AssignSubject::class, 'assign_subject_id', 'id');
    }
}
