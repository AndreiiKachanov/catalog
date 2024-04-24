<?php

namespace App\Http\Controllers\Admin\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Items\UpdateSettingsRequest;
use App\Models\Admin\Item\Item;
use App\Models\Admin\PriceHistory;
use App\Models\Admin\Setting;
use Carbon\Carbon;
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

        foreach ($rules as $fieldName => $fieldRules) {
            if (is_string($fieldRules) && Str::contains($fieldRules, 'required')) {
                $requiredFieldsArr[] = $fieldName;
            }

            if (is_array($fieldRules) &&  in_array('required', $fieldRules)) {
                $requiredFieldsArr[] = $fieldName;
            }
        }

        $priceHistories = PriceHistory::all();
        return view('admin.settings.index', compact('settings', 'requiredFieldsArr', 'priceHistories'));
    }

    /**
     * @param UpdateSettingsRequest $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(UpdateSettingsRequest $request)
    {
        $successMessage = [];
        $priceRegulation = (int)$request->get('price_regulation');

        try {
            if ($priceRegulation !== 0) {
                $currentDate = Carbon::now();
                $historyArr = [];
                DB::transaction(function () use ($currentDate, $priceRegulation, &$historyArr) {
                    Item::chunk(500, function ($items) use ($priceRegulation, $currentDate, &$historyArr) {
                        $items->each(function ($item) use ($priceRegulation, $currentDate, &$historyArr) {
                            $originalPrice = $item->getRawOriginal('price');
                            $newPrice = ($originalPrice / 100) * $priceRegulation + $originalPrice;
                            $historyArr[] = [
                                'item_id' => $item->id,
                                'old_price' => $originalPrice,
                                'new_price' => $newPrice,
                            ];
                            $item->update(['price' => $newPrice]);
                        });
                    });

                    $priceHistory = PriceHistory::create([
                        'percent' => $priceRegulation,
                        'price_updated_at' => $currentDate
                    ]);
                    $priceHistory->priceHistoryItems()->createMany($historyArr);
                });
                $displayPercent = ($priceRegulation > 0 ? '+' . $priceRegulation : $priceRegulation) . '%';
                $successMessage['success'][] = 'Успешная регулировка цен на ' . $displayPercent;
            }

            $inputs = $request->except(['price_regulation', '_token']);
            DB::transaction(function () use ($inputs) {
                foreach ($inputs as $key => $input) {
                    Setting::wherePropKey($key)->update(['prop_value' => $input]);
                }
            });
            $successMessage['success'][] = 'Настройки полей успешно обновлены.';
        } catch (Exception $exception) {
            writeErrorToFile($exception->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors('Ошибка обновления настроек. См. лог.');
        }
        return redirect()->route('admin.settings.index')->with($successMessage);
    }
}
