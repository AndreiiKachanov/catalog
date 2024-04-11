<?php

namespace App\Services;

class TestServiceToFacade
{
    //private $token;
    //public function __construct($token)
    //{
    //    $this->token = $token;
    //}

    public function test(): bool
    {
        //echo 'token = ' . $this->token;
        dd(111);
        return true;
    }
}
