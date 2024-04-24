<?php

namespace App\Http\Controllers\Admin\Items;

use App\Http\Controllers\Controller;
use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Token;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrdersController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $contacts = Order::has('orderItems')
            ->with(['orderItems.item', 'token.invoice'])
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination_default_value'));
        return view('admin.orders.index', compact('contacts'));
    }

    /**
     * @param Order $order
     * @return Application|Factory|View
     */
    public function show(Order $order)
    {
        $order->load([
            'token.invoice',
            'orderItems.item' => fn($query) => $query->withTrashed(),
            'orderItems.item.category' => fn($query) => $query->withTrashed(),
        ]);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index');
    }

    /**
     * Этот запрос вернет только те Token, у которых
     * есть элементы корзины (cartItems), но у контакта (contact),
     * связанного с этими Token, нет заказов (orders)
     *
     * @return Application|Factory|View
     */
    public function incompleteOrders()
    {
        $tokens = Token::with(['cartItems.item', 'invoice'])
            ->has('cartItems')
            ->doesntHave('order.orderItems')
            ->orderByDesc('created_at')
            ->paginate(config('app.pagination_default_value'));
        return view('admin.orders.incomplete', compact('tokens'));
    }

    /**
     * @param Token $token
     * @return Application|Factory|View
     */
    public function showIncompleteOrder(Token $token)
    {
        $token->load([
            'invoice',
            'order',
            'cartItems.item' => fn($query) => $query->withTrashed(),
            'cartItems.item.category' => fn($query) => $query->withTrashed(),
        ]);
        return view('admin.orders.show_incomplete', compact('token'));
    }
}
