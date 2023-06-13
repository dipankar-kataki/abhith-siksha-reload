<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonAttachment extends Model
{
    use SoftDeletes;
    protected $table = "lesson_attachments";
    protected $fillable = [
        'subject_lesson_id',
        'img_url',
        'video_thumbnail_image',
        'attachment_origin_url',
        'video_resize_480',
        'video_resize_720',
        'video_resize_1080',
        'video_origin_url',
        'video_duration',
        'attachment_type',
        'type',
        'progress_status',
        'free_demo'

    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class,'subject_lesson_id');
    }
    public function assignSubject()
    {
        return $this->belongsTo(assignSubject::class);
    }
}
