<?php

namespace App\UseCases;

use App\Services\TestService;

class TelegramNotificator implements MessangerNotificatorInterface
{
    public function send($message): bool
    {
        //dd(__CLASS__ . $message);
        return true;
    }
}
