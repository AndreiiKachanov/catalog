<?php

use App\Http\Controllers\Admin\HomeController as AdminHome;
use App\Http\Controllers\Admin\VisitorsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Items\CategoryController;
use App\Http\Controllers\Admin\Items\OrdersController;
use App\Http\Controllers\Admin\Items\SettingsController;
use App\Http\Controllers\Admin\Items\ItemsController;

Auth::routes();

Route::get('/login/phone', [LoginController::class, 'phone'])->name('login.phone');
Route::post('/login/phone', [LoginController::class, 'verify']);
Route::get('/verify/{token}', [RegisterController::class, 'verify'])->name('register.verify');

//Route::get('test123', function (\Illuminate\Http\Request $request, \App\UseCases\SendOrderService $service) {
//    dd($service->getIpAddressInfo($request->ip()));
//});

Route::view('/', 'layouts.app')->name('home');
Route::group(
    [
        'prefix'     => 'admin',
        'as'         => 'admin.',
        'namespace'  => 'Admin',
        'middleware' => ['auth', 'can:admin-panel']
    ],
    function () {
        Route::get('/', [AdminHome::class, 'index'])->name('home');

        //Настройки
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/', [SettingsController::class, 'update'])->name('update');
        });

        //Товары
        //Проверка наличие товаров в корзине перед удалением
        Route::get('items/check-before-remove/{item}', [ItemsController::class, 'checkBeforeRemove'])
            ->name('items.check-before-remove');
        Route::resource('items', ItemsController::class);

        //Категории
        Route::resource('categories', CategoryController::class);
        Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
            Route::post('/first', [CategoryController::class, 'first'])->name('first');
            Route::post('/up', [CategoryController::class, 'up'])->name('up');
            Route::post('/down', [CategoryController::class, 'down'])->name('down');
            Route::post('/last', [CategoryController::class, 'last'])->name('last');
        });
        Route::get('/categories/{category}/orders', [CategoryController::class, 'showOrders'])
            ->name('categories.show_orders');
        Route::get('/categories/{category}/items', [CategoryController::class, 'showItems'])
            ->name('categories.show_items');

        //Заказы
        Route::get('orders/incomplete', [OrdersController::class, 'incompleteOrders'])
            ->name('orders.incomplete');
        Route::get('orders/incomplete/{token}', [OrdersController::class, 'showIncompleteOrder'])
            ->name('orders.incomplete.show');
        Route::resource('orders', OrdersController::class);

        //Посетители
        Route::group(['prefix' => 'visitors', 'as' => 'visitors.'], function () {
            Route::get('/', [VisitorsController::class, 'index'])->name('index');
            Route::post('/', [VisitorsController::class, 'show'])->name('show');
        });

        //Пользователи
        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
    }
);

//Маршрут под vue-router - чтобы после перезагрузки работали страницы
Route::get('/{any}', fn() => view('layouts.app'))
    ->whereIn('any', [
        'cart',
        'order',
        'contacts',
        'search',
        'category/[0-9a-z\-]*',
        'product/[0-9a-z\-]*',
        //'admin/categories/[0-9]+/orders'
    ])
    ->name('any');
