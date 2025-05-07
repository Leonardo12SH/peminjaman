<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransaksiResult extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $user;
    protected $transaksi;
    protected $qrcode;
    public function __construct($user, $transaksi, $qrcode)
    {
        $this->user = $user;
        $this->transaksi = $transaksi;
        $this->qrcode = $qrcode;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Transaksi Result',
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
            view: 'mail.result',
            with: [
                'user_name' => $this->user->name,
                'no_transaksi' => $this->transaksi->no_transaksi,
                'menu_item' => $this->transaksi->transaksi_details[0]->menuitem->name,
                'start_time' => $this->transaksi->start_time,
                'total_price' => $this->transaksi->total_price,
                'status' => $this->transaksi->status == 1 ? 'Diterima' : ($this->transaksi->status == 2 ? 'Gagal' : 'Menunggu'),
                'qrcode' => $this->qrcode,
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
