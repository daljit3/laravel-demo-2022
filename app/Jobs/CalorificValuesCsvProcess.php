<?php

namespace App\Jobs;

use App\Models\CalorificValue;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalorificValuesCsvProcess implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $header;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($header, $data)
    {
        $this->data = $data;
        $this->header = $header;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $line) {
            $data = [
                Carbon::createFromFormat('d/m/Y', $line[1])->format('Y-m-d'), //applicable for
                str_replace('Calorific Value, ', '', $line[2]),  // area
                $line[3] // value
            ];
            $dbData = array_combine($this->header,$data);
            CalorificValue::create($dbData);
        }

    }
}
