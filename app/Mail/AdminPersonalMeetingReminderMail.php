<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPersonalMeetingReminderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $mailType;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $mailType)
    {
        $this->data = $data;
        $this->mailType = $mailType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: date('d-m-Y').' Upcoming '.$this->mailType.'Meeting Reminders',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_personal_meeting_reminderMail',
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

    public function build()
    {
        return $this->view('emails.admin_personal_meeting_reminderMail')
                    ->with('data', $this->data);
    }
}
