<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $urlPage;
    public $password;
    public function __construct($email,$password, $urlPage)
    {
        $this->email = $email;
        $this->password=$password;
        $this->urlPage = $urlPage;

    }

    public function build()
    {
        return $this->subject('Notification de Connexion')
                    ->view('notification.mail')
                    ->with([
                        'email' => $this->email,
                        'password'=>$this->password,
                        'urlPage' => $this->urlPage,
                    ]);
    }
}

