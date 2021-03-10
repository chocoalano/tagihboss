<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class TagihBossEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from('bpr.kreditmandiri.indonesia@tagihboss.com')
                   ->view('tagihbossMail')
                   ->with(
                    [
                        'name' => ucfirst(strtolower($this->user['nama'])),
                        'email' => ucfirst(strtolower($this->user['email'])),
                        'event' => 'Activity',
                    ]);
    }
}
