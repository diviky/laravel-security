<?php

declare(strict_types=1);

namespace Diviky\Security\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class NewDevice extends Notification
{
    use Queueable;

    /**
     * The authentication log.
     *
     * @var \Diviky\Security\Models\LoginHistory
     */
    public $history;

    /**
     * Create a new notification instance.
     *
     * @param \Diviky\Security\Models\LoginHistory $history
     */
    public function __construct($history)
    {
        $this->history = $history;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        $via = config('security.via');

        if (!empty($via)) {
            return $via;
        }

        return $notifiable->notifyNewDeviceLoginVia() ?: ['email'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(trans('security::messages.subject'))
            ->markdown('security::emails.security.new', [
                'account' => $notifiable,
                'history' => $this->history,
            ]);
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->from(config('app.name'))
            ->warning()
            ->content(trans('security::messages.content', ['app' => config('app.name')]))
            ->attachment(function ($attachment) use ($notifiable): void {
                $attachment->fields([
                    'Account' => $notifiable->email,
                    'Time' => carbon($this->history->created_at),
                    'IP Address' => $this->history->ip,
                    'Browser' => $this->history->browser,
                    'Location' => $this->history->location . ' ' . $this->history->region,
                ]);
            });
    }
}
