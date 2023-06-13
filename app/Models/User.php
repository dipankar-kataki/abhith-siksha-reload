<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'otp',
        'verify_otp',
        'type_id',
        'password',
        'is_activate',
    ];

    protected $guarded = [];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getFullName()
    {
        return "{$this->firstname}   {$this->lastname}";
    }

    public function knowledgeForumPost()
    {
        return $this->hasMany('App\Models\KnowledgeForumPost');
    }

    public function knowledgeForumComment()
    {
        return $this->hasMany('App\Models\KnowledgeForumComment');
    }

    public function userDetail()
    {
        return $this->hasOne(UserDetails::class);
    }
    public function assignSubject(){
        return $this->hasMany(TeacherAssignToSubject::class,'user_id','id');
    }
}
