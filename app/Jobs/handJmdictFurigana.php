<?php

namespace App\Jobs;

use App\Models\JmdictFurigana;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class handJmdictFurigana implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $text;
    protected $reading;
    protected $furigana;

    public function __construct($text, $reading, $furigana)
    {
        $this->text = $text;
        $this->reading = $reading;
        $this->furigana = $furigana;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::set($this->text, $this->furigana);
    }
}
