<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BarangDetailRestored extends Notification
{
    use Queueable;

    public $barang;
    public $idBarang;
    public $restoredBy;

    public function __construct($barang, $idBarang, $restoredBy)
    {
        $this->barang = $barang;
        $this->idBarang = $idBarang;
        $this->restoredBy = $restoredBy;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Barang Detail Dikembalikan',
            'message' => 'Salah satu detail barang telah dikembalikan (restore).',
            'nama_barang' => $this->barang ? $this->barang->namaBarang : '-',
            'id_barang' => $this->barang ? $this->barang->idBarang : $this->idBarang,
            'restored_by' => $this->restoredBy,
        ];
    }
    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
