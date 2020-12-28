<?php

namespace Wink\Mail;

use Illuminate\Mail\Mailable;

class SendAuthorCredentialsEmail extends Mailable
{
    public $name;
    public $email;
    public $password;
     
    public function __construct($name, $email, $password)
    {
       
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
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
        return $this->markdown('wink::emails.author-credentials')
                    ->subject('Science4all Login Details');
    }
}
