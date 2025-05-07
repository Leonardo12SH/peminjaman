<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransaksiMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;
    protected $transaksi;
    protected $transaksi_detail;
    public function __construct($user, $transaksi, $transaksi_detail)
    {
        $this->user = $user;
        $this->transaksi = $transaksi;
        $this->transaksi_detail = $transaksi_detail;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'PENGAJUAN BOOKING',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.transaksi',
            with: [
                'user_name' => $this->user->name,
                'user_telepon' => $this->user->telephone,
                'no_transaksi' => $this->transaksi->no_transaksi,
                'noted' => $this->transaksi->noted,
                'start_time' => $this->transaksi->start_time,
                'end_time' => $this->transaksi->end_time,
                'status' => $this->transaksi->status,
                'menu_item' => $this->transaksi_detail->menuitem->name
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
