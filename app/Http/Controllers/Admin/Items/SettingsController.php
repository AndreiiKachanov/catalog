<?php

namespace App\Http\Controllers\Admin\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Items\UpdateSettingsRequest;
use App\Models\Admin\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class SettingsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $settings = Setting::all();
        $request = new UpdateSettingsRequest();
        $rules = $request->rules();

        //массив обязательных полей
        $requiredFieldsArr = [];

        //dd($rules);
        foreach ($rules as $fieldName => $fieldRules) {
            if (!is_array($fieldRules) && Str::contains($fieldRules, 'required')) {
                $requiredFieldsArr[] = $fieldName;
            }
        }

        return view('admin.settings.index', compact('settings', 'requiredFieldsArr'));
    }

    /**
     * @param UpdateSettingsRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateSettingsRequest $request)
    {
        $inputs = $request->except('_token');

        try {
            DB::transaction(function () use ($inputs) {
                foreach ($inputs as $key => $input) {
                    Setting::wherePropKey($key)->update(['prop_value' => $input]);
                }
            });
            return redirect()->route('admin.settings.index')->with('success', 'Данные успешно сохранены');
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Ошибка обновления настроек. См. лог.');
        }
    }
}
