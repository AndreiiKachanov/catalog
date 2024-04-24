<?php

namespace Database\Seeders\Prod;

use App\Models\Admin\Setting;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        try {
            $data = Setting::on('mysql_prod')->get();
            $settings = [];
            foreach ($data as $setting) {
                $setting = $setting->getAttributes();
                $settings[] = [
                    'title' => $setting['title'],
                    'prop_key' => $setting['prop_key'],
                    'prop_value' => $setting['prop_value'],
                    'is_icon' => $setting['is_icon'],
                    'fa_icon' => $setting['fa_icon'],
                    'created_at' => $setting['created_at'],
                    'updated_at' => $setting['updated_at'],
                ];
            }
            DB::table(Setting::getTableName())->insert($settings);
        } catch (Exception $e) {
            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
            throw new Exception($errorMsg);
        }
    }
}
