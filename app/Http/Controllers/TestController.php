<?php

namespace App\Http\Controllers;

use App\Facades\TestFacade;
use App\Services\TestService;
use App\UseCases\MessangerNotificatorInterface;

class TestController
{

    //Инъекция класса TestService
    public function index(TestService $service)
    {
        $service->test();
        dd(2);
    }

    //Инъекция интерфейса
    public function index2(MessangerNotificatorInterface $notificator)
    {
        dd($notificator->send('test123'));
    }

    //Создание фассада
    public function index3()
    {
        TestFacade::test();
    }
}
