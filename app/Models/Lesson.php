<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;
    protected $table = "lessons";
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'parent_lesson_id',
        'board_id',
        'assign_class_id',
        'assign_subject_id',
        'content',
        'type',
        'teacher_id',
        'preview',
        'status'

    ];
    public static function getRules($type)
    {
        if ($type == "lesson-create" || $type == "update-lesson") {
            $lesson_rules = [
                'board_id' => 'bail|integer|required',
                'assign_class_id' => 'bail|integer|required',
                'assign_subject_id' => 'bail|integer|required',
                'name' => 'bail|unique:lessons|required',
                'image_url' => 'bail|required|mimes:png,jpg,jpeg',
                'video_url' => 'bail|required|mimes:mp4',
                'content' => 'bail|required',
            ];
            return $lesson_rules;
        }
        if ($type == "content-update") {
            $lesson_rules = [
                'parent_id' => 'bail|integer|required',
                'content' => 'bail|required'
            ];
            return $lesson_rules;
        }
        if ($type == "create-topic") {
            $lesson_rules_message = [
                'parent_id' => 'bail|required|integer|required',
                'content' => 'bail|required',
                'image_url' => 'bail|mimes:png,jpeg',
            ];
            return $lesson_rules_message;
        }
        if ($type == "create-sub-topic") {
            $lesson_rules_message = [
                'parent_id' => 'bail|required|integer|required',
                'parent_lesson_id' => 'bail|required|integer|required',
                'content' => 'bail|required',
                'image_url' => 'bail|mimes:png,jpeg',
            ];
            return $lesson_rules_message;
        }
    }
    public static function getRuleMessages($type)
    {
        if ($type == "lesson-create" || $type == "update-lesson") {
            $lesson_rules_message = [
                'board_id.required' => 'Please insert a valid Board',
                'assign_class_id.required' => 'Please insert a valid Class',
                'assign_subject_id.required' => 'Please insert a valid Subject',
                'name.required' => 'Please insert a valid Lesson Title',
                'name.unique'=>'This lesson name is already in used',
                'image_url.required' => 'Image Is required and it should be on png,jpeg format',
                'video_url.required' => 'Video  should be on mp4 format',
            ];
            return $lesson_rules_message;
        }
        if ($type == "content-update") {
            $lesson_rules_message = [
                'lesson_id.required' => 'Please insert a valid Lesson',
                'content.required' => 'Please insert a valid Content',
            ];
            return $lesson_rules_message;
        }
        if ($type == "create-topic") {
            $lesson_rules_message = [
                'parent_id.required' => 'Lesson is not valid',
                'content.required' => 'Please insert a valid Content',
                'image_url.required' => 'Image should be on png,jpeg format',
            ];
            return $lesson_rules_message;
        }
        if ($type == "create-sub-topic") {
            $lesson_rules_message = [
                'parent_id.required' => 'Lesson is not valid',
                'parent_lesson_id.required' => 'Topic is not valid',
                'content.required' => 'Please insert a valid Content',
                'image_url.required' => 'Image should be on png,jpeg format',
            ];
            return $lesson_rules_message;
        }
    }
    public static function storeLessonFile($document, $document_type)
    {
        $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();

        if ($document_type == "image") {
            $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            $document->move(public_path('/files/course/subject/lesson'), $new_name);
            $file = '/files/course/subject/lesson/' . $new_name;
            return $file;
        }
        if ($document_type == "video") {
            $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            $document->move(public_path('/files/course/subject/lesson'), $new_name);
            $video_url = '/files/course/subject/lesson/' . $new_name;
            return $video_url;
        }
    }
    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function assignClass()
    {
        return $this->belongsTo(AssignClass::class);
    }
    public function assignSubject()
    {
        return $this->belongsTo(AssignSubject::class);
    }
    public function topics()
    {
        return $this->hasMany(Lesson::class, 'parent_id')->where('parent_lesson_id', null);
    }
    public function subTopics()
    {
        return $this->hasMany(Lesson::class, 'parent_lesson_id');
    }
   
    public function lessonAttachment()
    {
        return $this->hasOne(LessonAttachment::class,'subject_lesson_id','id')->where('type',2);
    }

    
    public function assignTeacher(){
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }
    public function Sets(){
        return $this->hasMany(Set::class,'lesson_id','id');
    }
    public function activeSets(){
        return $this->hasMany(Set::class,'lesson_id','id')->where('is_activate',1);
    }
    public function parentLesson(){
        return $this->belongsTo(Lesson::class,'parent_id','id');
    }
}
