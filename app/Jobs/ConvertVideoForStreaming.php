<?php

namespace App\Jobs;
use Carbon\Carbon;
use FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $lesson_attachment;
    public function __construct($lesson_attachment)
    {
        $this->lesson_attachment=$lesson_attachment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lowBitrateFormat  = (new X264)->setKiloBitrate(500);
        $midBitrateFormat  = (new X264)->setKiloBitrate(1500);
        $highBitrateFormat = (new X264)->setKiloBitrate(3000);

        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->lesson_attachment->disk)
            ->open($this->lesson_attachment->path)

        // call the 'exportForHLS' method and specify the disk to which we want to export...
            ->exportForHLS()
            ->toDisk('streamable_videos')

       
            ->addFormat($lowBitrateFormat)
            ->addFormat($midBitrateFormat)
            ->addFormat($highBitrateFormat)

        
            ->save($this->lesson_attachment->id . '.m3u8');
    }
}
