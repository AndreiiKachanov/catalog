<?php

namespace App\UseCases;

interface MessangerNotificatorInterface
{
    public function send($message): bool;
}
