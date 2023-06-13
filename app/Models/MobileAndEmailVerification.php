<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileAndEmailVerification extends Model
{
    use SoftDeletes;
    protected $table="mobile_and_email_verifications";
    protected $fillable=[
        'id',
        'mobile',
        'email',
        'mobile_email_otp',
        'mobile_email_verification',
    ];
}
