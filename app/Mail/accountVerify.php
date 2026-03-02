<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class accountVerify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $mailSubject = null;
    private $layout = null;

    public function __construct(private $data)
    {
        $this->layout = !empty($data['layout']) ? $data['layout'] : 'sevn';
        $this->mailSubject = !empty($data['email_subject']) ? $data['email_subject'] : 'Test Mail';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        /*cc: ['testreceiver-cc@gmail.com'],
        bcc: ['testreceiver-bcc@gmail.com']*/
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.account-verify',
            with: ['layout' => $this->layout, 'data' => $this->data],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
