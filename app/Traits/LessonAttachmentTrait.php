<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Jobs\ConvertVideoForResolution;

trait LessonAttachmentTrait {

   
    public static function uploadAttachment($document, $document_type,$name_slug) {

        $new_name = date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();

        if ($document_type == "image") {
           
            $new_name = $name_slug.'_'.date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            $document->move(public_path('/files/course/subject/lesson'), $new_name);
            $file = '/files/course/subject/lesson/' . $new_name;
            return $file;
        }
        if ($document_type == "video") {
            $new_name = $name_slug.'_'.date('d-m-Y-H-i-s') . '_' . $document->getClientOriginalName();
            $document->move(public_path('/files/course/subject/lesson'), $new_name);
            $video_url = '/files/course/subject/lesson/' . $new_name;
            return $video_url;
        }

        return null;

    }

}