<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommonEmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $settings;
    public $user_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($template, $user_id , $settings)
    {
        $this->template = $template;
        $this->user_id  = $user_id;
        $this->settings = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(company_setting('mail_from_address',$this->user_id))->markdown('email.common_email_template')->subject($this->template->subject)->with(
            [
                'content' => $this->template->content,
                'mail_header' => (!empty($this->settings['company_name'])) ? $this->settings['company_name'] : env('APP_NAME'),
            ]
        );
    }
}
