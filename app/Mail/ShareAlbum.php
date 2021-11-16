<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareAlbum extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    private $path;
    private $data;

    /**
     * Create a new message instance.
     * @param string $subject
     * @param int $id
     * @param array $data
     */
    public function __construct(string $subject, int $id, array $data)
    {
        $this->subject = $subject;
        $this->path = "email." . $id;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->view($this->path)->with(['data' => $this->data]);
    }
}
