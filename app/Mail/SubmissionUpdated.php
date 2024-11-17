<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SubmissionUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Submission $submission
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Status Pengajuan Anda Telah Diperbarui',
            tags: ['submission', 'pengajuan'],
            metadata: [
                'id_pengajuan' => $this->submission->id
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.submission-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->submission->file_result) {
            $extension = pathinfo($this->submission->file_result, PATHINFO_EXTENSION);
            $fileName = $this->submission->category->name . '-' . $this->submission->student->fullname . '.' . $extension;

            $fileStoragePath = str_replace('/storage', 'public', $this->submission->file_result);
            $filePath = Storage::get($fileStoragePath);

            return [
                Attachment::fromData(fn () => $filePath, $fileName),
            ];
        } else {
            return [];
        }
    }
}
