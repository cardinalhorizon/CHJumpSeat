<?php

namespace Modules\CHJumpSeat\Notifications;

use App\Models\User;
use App\Notifications\Channels\Discord\DiscordMessage;
use Illuminate\Bus\Queueable;
use App\Contracts\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\CHJumpSeat\Models\CHJumpseatRequest;

/**
 * Class JumpSeatRequested
 * @package Modules\CHJumpSeat\Notifications
 */
class JumpSeatRequested extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public CHJumpseatRequest $jsr)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['discord_webhook'];
    }

    public function toDiscordChannel($notifiable): ?DiscordMessage
    {
        $dm = new DiscordMessage();
        return $dm->webhook(setting('notifications.discord_private_webhook_url'))
            ->warning()
            ->title('Jumpseat Requested: '.$this->jsr->user->ident.' - '.$this->jsr->user->name_private)
            ->author([
                'name'     => $this->jsr->user->ident.' - '.$this->jsr->user->name_private,
                'url'      => '',
                'icon_url' => $this->jsr->user->resolveAvatarUrl(),
            ])
            ->fields([
                'Current Airport' => $this->jsr->user->curr_airport_id,
                'Requested Airport' => $this->jsr->airport_id,
                'Reason' => $this->jsr->request_reason ?? "Not Filled"
            ]);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', 'https://laravel.com')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
