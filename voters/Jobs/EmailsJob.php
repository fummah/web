<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\EmailNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

//class EmailsJob implements ShouldQueue
class EmailsJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
private $dda;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($dda)
    {
        $this->dda=$dda;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		$data=$this->dda;
        	$users=User::chunk(10,function($users) use($data){
		$recepients=$users->pluck('email');
		Notification::route('mail',$recepients)->notify(new EmailNotification($data));
	});
    }
}
