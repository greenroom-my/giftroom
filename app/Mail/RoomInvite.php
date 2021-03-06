<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoomInvite extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var
     */
    public $guestEmail;
    public $hostName;
    public $roomId;

    /**
     * Create a new message instance.
     *
     * @param $name
     */
    public function __construct($guestEmail, $hostName, $roomId)
    {
        $this->guestEmail = $guestEmail;
        $this->hostName = $hostName;
        $this->roomId= $roomId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('postmaster@giftroom.party', 'Giftroom')
            ->markdown('emails.room.invite')
            ->subject('Hey, ' . $this->hostName . ' invited you to a party!');
    }
}
