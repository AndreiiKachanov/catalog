<?php

namespace App\Services;

use App\Models\Admin\Setting;

class SettingsService
{
    private static $priceIncrease = null;
    private static $priceIncrease2 = null;

    public static function getPriceIncrease()
    {
        if (self::$priceIncrease === null) {
            self::$priceIncrease = (int)Setting::firstWhere('prop_key', 'price_increase')->prop_value;
        }
        return self::$priceIncrease;
    }

    public static function getPriceIncrease2()
    {
        if (self::$priceIncrease2 === null) {
            self::$priceIncrease2 = (int)Setting::firstWhere('prop_key', 'price_increase2')->prop_value;
        }
        return self::$priceIncrease2;
    }
}
