<?php

namespace App\Notifications;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\TentSites;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DailyStatistics extends Notification
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
            //
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

        $dashboard = new DashboardController();
        $responseData = json_decode($dashboard->getStatusData());

        $newUsers = \DB::table('users')->whereDate('created_at','>=',date('Y-m-d'))
            ->whereTime('created_at','>=','07:00:00')->count();
        $totalUsers = \DB::table('users')->count();
        $newTentSites = \DB::table(TentSites::DB)->whereDate('created_at','>=',date('Y-m-d'))
            ->whereTime('created_at','>=','07:00:00')->count();
        $totalTentSites = \DB::table(TentSites::DB)->count();

        $data = [$newUsers, $totalUsers, $newTentSites, $totalTentSites, $responseData];

        return (new SlackMessage)
            ->success()
            ->content('Daily statistics!')
            ->attachment(function ($attachment) use ($data, $notifiable) {
                $attachment->title(date('Y-m-d'))
                    ->fields([
                        'New users' => $data[0],
                        'Total users' => $data[1],
                        'New tentsites' => $data[2],
                        'Total tentsites' => $data[3],
                        'Apdex' => $data[4]->apdex.' / 1',
                        'Response time' => $data[4]->timings->total.' ms'
                    ]);
            });
    }
}
