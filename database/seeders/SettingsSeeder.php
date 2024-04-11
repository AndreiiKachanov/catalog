<?php

namespace Database\Seeders;

use App\Models\Admin\Setting;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (Setting::count() != 0) {
            throw new Exception(Setting::getTableName() . ' table is not empty. Stop all seeds!!!');
        }

        try {
            Setting::create([
                'title' => 'Наценка (%)',
                'prop_key' => 'price_increase',
                'is_icon' => false,
                'prop_value' => '0'
            ]);
            Setting::create([
                'title' => 'Сумма минимального заказа (₽). Если установлена, то минимальное кол-во товаров не проверяется',
                'prop_key' => 'min_order_cost',
                'prop_value' => 5001,
                'is_icon' => false,
            ]);
            Setting::create([
                'title' => 'Номер телефона',
                'prop_key' => 'phone_number',
                'prop_value' => '+79495468124',
                'is_icon' => true,
                'fa_icon' => 'fa-solid fa-square-phone'
            ]);
            Setting::create([
                'title' => 'Instagram',
                'prop_key' => 'instagram',
                'prop_value' => '+79495468124',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-instagram'
            ]);
            Setting::create([
                'title' => 'Whatsapp',
                'prop_key' => 'whatsapp',
                'prop_value' => '+79495468124',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-square-whatsapp'
            ]);
            Setting::create([
                'title' => 'Сайт',
                'prop_key' => 'site',
                'prop_value' => 'https://yandex.ru',
                'is_icon' => true,
                'fa_icon' => 'fa-solid fa-globe'
            ]);
            Setting::create([
                'title' => 'Viber',
                'prop_key' => 'viber',
                'prop_value' => '+79495468124',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-viber'
            ]);
            Setting::create([
                'title' => 'Telegram',
                'prop_key' => 'telegram',
                'prop_value' => '+79495468124',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-telegram'
            ]);
            Setting::create([
                'title' => 'Tiktok',
                'prop_key' => 'tiktok',
                'prop_value' => 'https://www.tiktok.com',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-tiktok'
            ]);
            Setting::create([
                'title' => 'Youtube',
                'prop_key' => 'youtube',
                'prop_value' => 'https://www.youtube.com',
                'is_icon' => true,
                'fa_icon' => 'fa-brands fa-youtube'
            ]);
            Setting::create([
                'title' => 'Общая информация',
                'prop_key' => 'custom_text',
                'prop_value' => 'Общая информация',
                'is_icon' => true,
                'fa_icon' => 'fa-solid fa-circle-info'
            ]);
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
