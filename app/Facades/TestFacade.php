<?php

namespace App\Facades;

use App\Services\TestServiceToFacade;
use Illuminate\Support\Facades\Facade;

final class TestFacade extends Facade
{
    /**
     * @method static bool test()
     * @see TestServiceToFacade
     */
    protected static function getFacadeAccessor()
    {
        return TestServiceToFacade::class;
    }
}
