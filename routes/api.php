 <?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

$missing = fn(Request $request) => response('Missing route. Not found111', 404);

Route::post('test', fn(Request $request) => dd($request->all()))->name('api.test');

Route::get('items', [ApiController::class, 'getItems'])->name('api.items');
 Route::get('items/category/{category:slug}', [ApiController::class, 'getItemsFromParentCategoryAndSubcategories'])
     ->name('api.get-items-from-category');

Route::get('get-categories', [ApiController::class, 'getCategories'])->name('api.get-categories');
Route::get('get-settings', [ApiController::class, 'getSettings'])->name('api.get-settings');

Route::group(
    [
        'prefix'     => 'cart',
        'as'         => 'api.cart.',
        //'middleware' => ['throttle:token']
    ],
    function () use ($missing) {
        Route::get('load', [ApiController::class, 'cartLoad'])
            ->name('cart-load')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::post('add', [ApiController::class, 'addsItemsToCart'])
            ->name('add')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::post('remove', [ApiController::class, 'removeItemsFromCart'])
            ->name('remove')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::post('set-cnt', [ApiController::class, 'setCnt'])
            ->name('set-cnt')
            ->missing($missing);
        Route::post('set-order-info', [ApiController::class, 'setOrderInfo'])
            ->name('set-order-info')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::get('invoice/load', [ApiController::class, 'getBillNumber'])
            ->name('get-bill-number')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::post('store', [ApiController::class, 'storeOrder'])
            ->name('store-order')
            ->middleware(['throttle:token'])
            ->missing($missing);
        Route::get('load-order', [ApiController::class, 'loadOrder'])
            ->name('load-order')
            ->middleware(['throttle:token'])
            ->missing($missing);
    }
);





Route::fallback(fn() => 'fallback route');
