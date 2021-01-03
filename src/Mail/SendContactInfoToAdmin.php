<?php

namespace Wink\Mail;

use Illuminate\Mail\Mailable;

class SendContactInfoToAdmin extends Mailable
{
    public $name;
    public $email;
    public $bio;
     
    public function __construct($data)
    {
       
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->bio = $data['bio'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('Science4All Login Details')
        //     ->view('wink::emails.author-credentials');
        return $this->markdown('wink::emails.contact-info')
                    ->subject('Science4all Contact Info');
    }
}
