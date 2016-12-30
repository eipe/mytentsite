<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewTentSite extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }


    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $url = url('storage/images/tentsites/'.$notifiable->img_location);

        return (new SlackMessage)
            ->success()
            ->content('New tentsite added!')
            ->attachment(function ($attachment) use ($url, $notifiable) {
                $attachment->title($notifiable->location_name, $url)
                    ->fields([
                        'Date' => date('d.m.Y'),
                        'Id' => $notifiable->id,
                        'Reported by' => $notifiable->reported_by,
                        'Latitude' => $notifiable->latitude,
                        'Longitude' => $notifiable->longitude,
                        'Map' => 'google.com/maps/?q='.$notifiable->latitude.','.$notifiable->longitude,
                        'Accept' => env('APP_URL').'/admin/approve/'. $notifiable->id
                    ]);
            });
    }
}
