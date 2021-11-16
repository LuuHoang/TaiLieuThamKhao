<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $_otpCode;

    /**
     * Create a new message instance.
     *
     * @param String $otpCode
     */
    public function __construct(String $otpCode)
    {
        $this->_otpCode = $otpCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('[Album-Maker][パスワード変更]')->markdown('emails.forgot_password')->with([
            'otp_code' => $this->_otpCode
        ]);
    }
}
