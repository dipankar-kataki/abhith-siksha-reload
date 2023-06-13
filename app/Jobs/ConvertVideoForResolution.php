<?php

namespace App\Jobs;

use App\Models\Lesson;
use FFMpeg;
use App\Models\LessonAttachment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg as SupportFFMpeg;

class ConvertVideoForResolution implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  
    public $lesson_attachment;
    public $x_dimension;
    public $y_dimension;
    public $slug;
  
    public function __construct(LessonAttachment $lesson_attachment,$x_dimension,$y_dimension,$slug)
    {
        
       $this->lesson_attachment=$lesson_attachment;
       $this->x_dimension=$x_dimension;
       $this->y_dimension=$y_dimension;
      
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $lowBitrateFormat = (new X264())->setKiloBitrate(500);

        // open the uploaded video from the right disk...
        FFMpeg::open($this->lesson_attachment->attachment_origin_url)

            // add the 'resize' filter...
            ->addFilter(function ($filters) {
                $filters->resize(new Dimension($this->x_dimension, $this->y_dimension));
            })

            // call the 'export' method...
            ->export()

            // tell the MediaExporter to which disk and in which format we want to export...
            ->toDisk('public')
            ->inFormat($lowBitrateFormat)

            // call the 'save' method with a filename...
            ->save($this->lesson_attachment->id.'_'.$this->slug.'_'.$this->y_dimension .'.mp4');
            
            if($this->y_dimension==480){
                $this->lesson_attachment->update([
                    'video_resize_480' => $this->lesson_attachment->id.'_'.$this->slug.'_'.$this->y_dimension .'.mp4',
                ]);
            }elseif($this->y_dimension==720){
                $this->lesson_attachment->update([
                    'video_resize_720' => $this->lesson_attachment->id.'_'.$this->slug.'_'.$this->y_dimension .'.mp4',
                ]);
            }else{
                $this->lesson_attachment->update([
                    'video_resize_1080' => $this->lesson_attachment->id.'_'.$this->slug.'_'.$this->y_dimension .'.mp4',
                ]);
            }
           
        
    }
}
