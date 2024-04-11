@php
    /** @var \App\Models\Admin\Cart\Token $token */
    /** @var \App\Models\Admin\Cart\CartItem $cartItem */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
        table.bottom {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }


        caption {
            font-size: 1.5em;
            margin: .5em 0 .75em;
        }

        table.bottom tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        table.bottom th,
        table.bottom td {
            padding: .625em;
            text-align: center;
        }

        table.bottom th {
            font-size: .85em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            max-width: 100%;
            height: auto;
        }

        /*.img-fluid {*/
        /*    max-width: 100%;*/
        /*    height: auto;*/
        /*}*/
        img {
            vertical-align: middle;
            border-style: none;
            height: auto;
        }

        table.bottom tr td:nth-child(2) {
            text-align: left;
        }

        tr.all_sum td:first-child {
            padding-left: 23px;
        }

        tr.all_sum td:last-child {
            padding-right: 34px;
        }

        @media screen and (max-width: 600px) {

            tr.all_sum td:first-child {
                padding-left: 0;
            }

            tr.all_sum td:last-child {
                padding-right: 0;
            }

            table.bottom tr td:first-child {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
            }

            img {
                max-width: 35% !important;
                vertical-align: middle !important;
                border-style: none !important;
            }


            table.bottom {
                border: 0;
            }

            table.bottom tr td:nth-child(2) {
                text-align: right;
            }

            caption {
                font-size: 1.3em;
            }

            table.bottom thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table.bottom tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            table.bottom td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }

            table.bottom td::before {
                /*
                * aria-label has no advantage, it won't be read inside a table
                content: attr(aria-label);
                */
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table.bottom td:last-child {
                border-bottom: 0;
            }
        }
    </style>
@endsection

@section('content')

    @include('admin.orders._nav_incomplete')

    <table class="table table-bordered table-striped">
        <caption style="caption-side: top; text-align: center; color: #000">Заказ
            № {{ $token->invoice->bill_number }}</caption>
        <tbody>
        <tr>
            <td>№ заказа</td>
            <td>{{ $token->invoice->bill_number }}</td>
        </tr>
        <tr>
            <td>Имя</td>
            <td>{{ $token?->order->name ?? '' }}</td>
        </tr>
        <tr>
            <td>Номер телефона</td>
            <td>{{ $token?->order->phone ?? '' }}</td>
        </tr>
        <tr>
            <td>Город</td>
            <td>{{ $token?->order->city ?? '' }}</td>
        </tr>
        <tr>
            <td>Улица</td>
            <td>{{ $token?->order->street ?? '' }}</td>
        </tr>
        <tr>
            <td>Дом</td>
            <td>{{ $token?->order->house_number ?? '' }}</td>
        </tr>
        <tr>
            <td>Транспортная компания</td>
            <td>{{ $token?->order->transport_company ?? '' }}</td>
        </tr>
        </tbody>
    </table>

    @if($token->cartItems->count())
        <table class="bottom">
            <caption style="caption-side: top; text-align: center; color: #000">Список товаров</caption>
            <thead>
            <tr>
                <th scope="col">Дата изменения</th>
                <th scope="col">Фото</th>
                <th scope="col">Название</th>
                <th scope="col">Артикул</th>
                <th scope="col">Цена</th>
                <th scope="col">Кол-во</th>
                <th scope="col">Сумма (₽)</th>
                <th scope="col">Категория</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($token->cartItems as $cartItem)
                <tr>
{{--                    @if($cartItem->item)--}}
                        <td data-label="Дата изменения">
                            {{ date_format($cartItem->updated_at, 'd.m.Y H:i:s') }}
                        </td>
                        <td data-label="Фото">
                            @if($cartItem->item->deleted_at)
                                <img
                                    src="{{ Storage::disk('uploads')->url($cartItem->item->img) }}"
                                    class="img-thumbnail"
                                    alt="{{ $cartItem->item->title }}"
                                >
                            @else
                                <a href="{{ route('admin.items.show', $cartItem->item) }}">
                                    <img
                                        src="{{ Storage::disk('uploads')->url($cartItem->item->img) }}"
                                        class="img-thumbnail"
                                        alt="{{ $cartItem->item->title }}"
                                    >
                                </a>
                            @endif
                        </td>
                        <td data-label="Название">
                            @if($cartItem->item->deleted_at)
                                <p style="color: red;">{{ $cartItem->item->title }} (удален {{ $cartItem->item->deleted_at->format('d.m.Y') }})</p>
                            @else
                                <a href="{{ route('admin.items.show', $cartItem->item) }}">{{ $cartItem->item->title }}</a>
                            @endif
                        </td>
                        <td data-label="Артикул">
                            {{ $cartItem->item->article_number }}
                        </td>
                        <td data-label="Цена">
                            {{ $cartItem->item->price }} ₽
                        </td>
                        <td data-label="Кол-во">
                            {{ $cartItem->cnt }}
                        </td>
                        <td data-label="Сумма">{{ number_format($cartItem->item->price * $cartItem->cnt, 0, ',', ' ') }}
                            ₽
                        </td>
                        <td data-label="Категория">
                            @if($cartItem->item->category->deleted_at)
                                <span style="color: red;">{{ $cartItem->item->category->title }} (удалена {{ $cartItem->item->category->deleted_at->format('d.m.Y') }})</span>
                            @else
                                <a href="{{ route('admin.categories.show', $cartItem->item->category) }}">
                                    {{ $cartItem->item->category->title }}
                                </a>
                            @endif
                        </td>
                </tr>
            @endforeach
            <tr class="all_sum">
                <td style="text-align: left"><strong>Всего:</strong></td>
                <td style="text-align: right;" colspan="7">
                    <strong>
                        {{ number_format($token->cartItems->sum(fn ($cartItem) =>  $cartItem->cnt * $cartItem->item?->price), 0, ',', ' ') }}
                        ₽
                    </strong>
                </td>
            </tr>
            </tbody>
        </table>
    @else
        <h3 class="text-center">Товаров в корзине нет</h3>
    @endif

@endsection
