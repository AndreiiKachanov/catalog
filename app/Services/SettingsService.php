<?php

namespace App\Services;

use App\Models\Admin\Setting;

class SettingsService
{
    private static ?int $priceIncrease = null;

    public static function getPriceIncrease(): ?int
    {
        if (self::$priceIncrease === null) {
            self::$priceIncrease = (int)Setting::firstWhere('prop_key', 'price_increase')->prop_value;
        }
        return self::$priceIncrease;
    }
}
