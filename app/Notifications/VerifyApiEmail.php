<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyApiEmail extends BaseVerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verify.email', // nom de ta route de verification
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey()]
        );
    }

    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Vérifiez votre adresse e-mail')
            ->line('Cliquez sur le lien ci-dessous pour vérifier votre adresse e-mail.')
            ->action('Vérifier Email', $url)
            ->line("Si vous n'avez pas créé de compte, ignorez cet email.");
    }
}
