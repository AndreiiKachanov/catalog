@php
    /** @var \App\Models\Admin\Cart\Order\Order $order */
    /** @var \App\Models\Admin\Cart\Order\OrderItem $ord */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
    </style>
@endsection

@section('content')
    @include('admin.orders._nav')

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <td>№ заказа</td>
            <td>{{ $order->token->invoice->bill_number }}</td>
        </tr>
        <tr>
            <td>Имя</td>
            <td>{{ $order->name }}</td>
        </tr>
        <tr>
            <td>Контакты</td>
            <td>{{ $order->contact }}</td>
        </tr>
        <tr>
            <td>Город</td>
            <td>{{ $order->city }}</td>
        </tr>
        <tr>
            <td>Улица</td>
            <td>{{ $order->street }}</td>
        </tr>
        <tr>
            <td>Дом</td>
            <td>{{ $order->house_number }}</td>
        </tr>
        <tr>
            <td>Транспортная компания</td>
            <td>{{ $order->transport_company }}</td>
        </tr>
        </tbody>
    </table>

    <table class="table table-bordered table-striped table-image">
        <thead>
        <tr>
            <th style="text-align: right">Продукт</th>
            <th>Артикул</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($order->orders as $ord)
            <tr>
                <td>
                    <img
                        src="{{ Storage::disk('uploads')->url($ord->item->img) }}"
                        class="img-fluid img-thumbnail"
                        style="max-width: 15%; margin-right: 15px;"
                        alt="{{ $ord->item->title }}"
                    >
                    {{ $ord->item->title }}
                </td>
                <td>{{ $ord->item->article_number }}</td>
                <td>{{ $ord->item->price }} ₽</td>
                <td>{{ $ord->cnt }}</td>

                <td>{{ $ord->item->price * $ord->cnt }} ₽</td>
            </tr>
        @endforeach
        <tr>
            <td>Всего:</td>
            <td style="text-align:right;" colspan="5">
                {{ $order->orders->sum(fn($item) => $item->item->price * $item->cnt) }} ₽
            </td>
        </tr>
        </tbody>
    </table>
@endsection
