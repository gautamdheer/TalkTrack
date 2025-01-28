<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalReviewed extends Notification
{
    private $proposal;

    public function __construct($proposal)
    {
        $this->proposal = $proposal;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Talk Proposal Has Been Reviewed')
            ->line('Your proposal "' . $this->proposal->title . '" has received a new review.')
            ->action('View Review', url('/proposals/' . $this->proposal->id))
            ->line('Thank you for your submission!');
    }
}
