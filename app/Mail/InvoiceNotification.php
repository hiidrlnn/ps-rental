<?php

namespace App\Mail;

use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rental;
    public $payment;
    public $type; // 'rental' or 'fine'

    /**
     * Create a new message instance.
     */
    public function __construct(Rental $rental, Payment $payment, $type = 'rental')
    {
        $this->rental = $rental;
        $this->payment = $payment;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'rental' ? 'Invoice Penyewaan PS Berhasil!' : 'Notifikasi Pembayaran Denda PS';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.invoice',
            with: [
                'rental' => $this->rental,
                'payment' => $this->payment,
                'type' => $this->type,
            ],
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