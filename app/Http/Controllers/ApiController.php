<?php

namespace App\Http\Controllers;

use App\Exceptions\MyHttpResponseException;
use App\Http\Resources\ItemResource;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\SettingsResource;
use App\Models\Admin\Cart\CartItem;
use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Token;
use App\Models\Admin\Item\Category;
use App\Models\Admin\Item\Item;
use App\Models\Admin\Setting;
use App\UseCases\ApiService;
use App\UseCases\SendOrderService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

/**
 * @property ApiService $service
 * @property SendOrderService $sendOrderService
 */
class ApiController extends Controller
{
    private string $cartTokensTableName;
    private string $itemsTableName;
    private ApiService $service;
    private SendOrderService $sendOrderService;

    /**
     * @param ApiService $service
     * @param SendOrderService $sendOrderService
     */
    public function __construct(ApiService $service, SendOrderService $sendOrderService)
    {
        $this->service = $service;
        $this->sendOrderService = $sendOrderService;
        $this->cartTokensTableName = Token::getTableName();
        $this->itemsTableName = Item::getTableName();
    }

    /**
     * Метод возвращает товары добавленные в корзину.
     * Принимаем token. Возвращаем json с информацией о товарах в корзине.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function cartLoad(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
            ]);

            $oldTokenString = $request->get('token');
            //Токен может приходить 'null' или (32 символа + быть в бд)
            if (!$this->service->isValidToken($oldTokenString)) {
                throw new MyHttpResponseException(
                    'Validation error',
                    'Попытка зайди с "левым" токеном! Входящий token не равен null или его нет в бд',
                    422
                );
            }

            $tokenIsNull = $oldTokenString === 'null';
            //Если токен есть
            if (!$tokenIsNull) {
                $oldToken = Token::with([
                    'orderItems',
                    'cartItems' => fn($query/** @var CartItem $query */) => $query->select([
                        'token_id',
                        'item_id as id',
                        'cnt'
                    ])->whereHas('item'),
                ])->firstWhere('token', $oldTokenString);
                //Если заказ уже выполнен - кидаем исключение, чтобы не "подсмотрели" заказ через адресную строку
                if ($oldToken->orderItems()->count() > 0) {
                    throw new MyHttpResponseException(
                        'Validation error',
                        '$oldToken->order !== null && $oldToken->order->orderItems->isNotEmpty()',
                        422
                    );
                }
            }
            //Если $tokenIsNull === false, значит входящий токен уже существует в бд, иначе
            //сработало бы исключение MyHttpResponseException
            $json = [
                //needUpdateToken = true - значит токена нет в бд.
                //needUpdateToken = false - значит токен есть в бд
                'needUpdateToken' => $tokenIsNull,
                //возвращаем массив id, cnt товаров, добавленных в корзину
                'cart' => !$tokenIsNull
                    ? $oldToken->cartItems
                        ->each(fn ($item) => $item->makeHidden('token_id'))
                        ->toArray()
                    : [],
                //возвращаем товары с полным описание для сохранения в Vuex
                'products' => !$tokenIsNull
                    ? ItemResource::collection(Item::find($oldToken->cartItems))
                    : [],
                //возвращаем старый или новый токен
                'token' => !$tokenIsNull ? $oldTokenString : $this->service->generateNewToken($request),
            ];

            //Обновляем 'время последнего визита и кол-во посещений'
            $token = Token::firstWhere('token', $json['token']);
            $token->update(['last_visit' => Carbon::now()]);
            $token->increment('visits_count');
            return response()->json($json);
        } catch (ValidationException $e) {
            throw new MyHttpResponseException($e->getMessage(), null, 422);
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function addsItemsToCart(Request $request)
    {
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
                'id'    => "required|exists:$this->itemsTableName,id",
                'cnt'   => 'required|integer|min:1|max:65535',
            ]);

            $token = Token::firstWhere('token', $request->get('token'));
            $item = Item::firstWhere('id', $request->get('id'));
            $cnt = $request->get('cnt');

            $cartItem = new CartItem(['token_id' => $token->id, 'item_id' => $item->id, 'cnt' => $cnt]);
            return response($cartItem->save() ? 'true' : 'false', 200);
        } catch (Exception $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function removeItemsFromCart(Request $request)
    {
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
                'id'    => "required|exists:$this->itemsTableName,id"
            ]);
            $token = Token::firstWhere('token', $request->get('token'));
            $item = Item::firstWhere('id', $request->get('id'));
            return response($token->cartItems()->where('item_id', $item->id)->delete() ? 'true' : 'false', 200);
        } catch (Exception $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function setCnt(Request $request)
    {
        //Используем валидацию + try catch в контроллере для отлова ошибок, возникающих с бд во
        // время валидации (exists:carts_tokens и exists:items)
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
                'id'    => "required|exists:$this->itemsTableName,id",
                'cnt'   => 'required|integer|min:1|max:65535',
            ]);

            $token = Token::firstWhere('token', $request->get('token'));
            $result = $token->cartItems()
                ->where('item_id', $request->get('id'))
                ->update(['cnt' => $request->get('cnt')]);

            return response($result ? 'true' : 'false', 200);
          //Обрабатывает ошибки валидации
        } catch (ValidationException $e) {
            throw new MyHttpResponseException($e->getMessage(), null, 422);
        } catch (QueryException $e) { // Обрабатывает ошибки связанные с бд
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function setOrderInfo(Request $request)
    {
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
                'value'    => 'nullable|string:max:255',
            ]);

            // Проверка $request->get('field'). Должен быть равен имени колонки в order_contacts таблице
            $tableName = Order::getTableName();
            $columnNames = Schema::getColumnListing($tableName);
            $excludedColumns = ['id', 'token_id', 'created_at', 'updated_at', 'deleted_at'];
            $filteredColumns = array_diff($columnNames, $excludedColumns);

            if (!in_array($request->get('field'), $filteredColumns)) {
                throw new MyHttpResponseException('Validation error', null, 422);
            }

            $token = Token::firstWhere('token', $request->get('token'));
            return response(
                $token->order()->updateOrCreate([], [$request->get('field') => $request->get('value')])
                    ? 'true'
                    : 'false',
                200
            );
        } catch (ValidationException $e) {
            throw new MyHttpResponseException($e->getMessage(), null, 422);
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getBillNumber(Request $request)
    {
        //Если приходит запрос на данный маршрут - значит токен по-любому есть в бд
        //Если токен есть в таблице invoice - вернуть старый bill_number, иначе новый
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
            ]);
            $token = Token::firstWhere('token', $request->get('token'));
            //если у токена нет invoice, создаем новый, иначе возвращаем существующий
            $invoice = $this->service->getInvoice($token);
            return response()->json(['bill_number' => $invoice->bill_number]);
        } catch (ValidationException $e) {
            throw new MyHttpResponseException($e->getMessage(), null, 422);
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function storeOrder(Request $request)
    {
        //Используем валидацию + try catch в контроллере для отлова ошибок, возникающих с бд во
        // время валидации (exists:carts_tokens)
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'transport_company' => 'required|string|max:255',
                'token' => "required|size:32|exists:$this->cartTokensTableName,token",
                'items' => 'required|array|min:1'
            ]);

            $oldToken = Token::with([
                'cartItems' => function ($query) {
                    $query->select(['id as cart_item_id', 'token_id', 'item_id as id', 'cnt', ])->whereHas('item');
                },
                'order'
            ])->firstWhere('token', $request->get('token'));
            $dbArr = $oldToken->cartItems->toArray();
            $frontArr = $request->get('items');

            if (count($dbArr) !== count($frontArr)) {
                throw new MyHttpResponseException('Validation error', 'count($dbArr) !== count($frontArr)', 422);
            }

            foreach ($dbArr as $k => $v) {
                if (!($dbArr[$k]['id'] === $frontArr[$k]['id'] && $dbArr[$k]['cnt'] === $frontArr[$k]['cnt'])) {
                    throw new MyHttpResponseException(
                        'Validation error',
                        'id и cnt из бд не соответствуют id и cnt с фронта',
                        422
                    );
                }
            }

            $items = array_map(function ($item) {
                return [
                    'item_id' => $item['id'],
                    'cnt' => $item['cnt'],
                    'cart_item_id' => $item['cart_item_id']
                ];
            }, $dbArr);
            $oldToken->order->orderItems()->createMany($items);
            $newToken = $this->service->generateNewToken($request);
            //Обрабатывает ошибки валидации
        } catch (ValidationException $e) {
            throw new MyHttpResponseException($e->getMessage(), null, 422);
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }

        $this->sendOrderService->sendTelegram($oldToken->order);
        $this->sendOrderService->sendEmail($oldToken->order);

        return response()->json([
            'new_token' => $newToken,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function loadOrder(Request $request)
    {
        try {
            $request->validate([
                'token' => "required|size:32|exists:$this->cartTokensTableName,token"
            ]);
            $token = Token::firstWhere('token', $request->get('token'));
            $existsOrder = $token->order()->exists();
            $response = $existsOrder
                ? $token->order->only(['name', 'phone', 'city', 'street', 'house_number', 'transport_company'])
                : [];
            return response()->json($response);
        } catch (ValidationException $e) {
            throw new MyHttpResponseException(
                'Validation error',
                $e->getMessage(),
                422
            );
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getCategories()
    {
        try {
            return CategoriesResource::collection(Category::defaultOrder()->get());
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getSettings()
    {
        try {
            return SettingsResource::collection(
                Setting::whereNotNull('prop_value')
                    ->whereIsIcon(true)
                    ->orWhere('prop_key', 'min_order_cost')
                    ->get()
            );
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getItems()
    {
        try {
            return ItemResource::collection(Item::orderByDesc('created_at')->get());
        } catch (QueryException $e) {
            throw new MyHttpResponseException('Database Error. See logs', $e->getMessage(), 500);
        }
    }
}
