<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\GenericMailable;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data for the job.
     *
     * @var array mail data
     */
    public $mail;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $mail)
    {
        //print_r($mail);die;
         $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $mailable = new GenericMailable(
            $this->mail['subject'],
            $this->mail['view'],
            $this->mail['view_data'],
            ($this->mail['attachment'] ?? [])
        );

        Mail::to($this->mail['to_email'])->send($mailable);
        
    }


     /**
     * The job failed to process.
     *
     * @param \Exception $exception The exception object.
     *
     * @return void
     */
    // public function failed(\Exception $exception)
    // {
    //     $view_data   = [
    //         'job_exception'   => $exception,
    //         'tracking_params' => '',
    //     ];
    //     $mailer_name = 'failed_job';

    //     // Mail template params.
    //     $mailer = Helper::getMailer($mailer_name);
    //     if (count($mailer) === 0) {
    //         \Log::Error('Mailer Not found: '.$mailer_name);
    //     }

    //     $mailable = new GenericMailable(
    //         $mailer['subject'],
    //         $mailer['view'],
    //         $view_data
    //     );

    //     Mail::to('singhalsantosh9@gmail.com')->send($mailable);

    // }//end failed()
}
