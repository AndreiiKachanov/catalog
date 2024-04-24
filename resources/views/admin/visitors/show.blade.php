@php
    /** @var \App\Models\Admin\Cart\Order\Order $order */
    /** @var \App\Models\Admin\Cart\Order\OrderItem $ord */
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
    @include('admin.orders._nav')
    <table class="table table-bordered table-striped">
        <caption style="caption-side: top; text-align: center; color: #000">Заказ
            № {{ $order->token->invoice->bill_number }}</caption>
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
            <td>Номер телефона</td>
            <td>
                <a href="tel:{{ $order->phone }}"> {{ $order->phone }}</a>
            </td>
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
    <div class="col-12 d-flex justify-content-start mt-3">
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="ml-5">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger pl-4 pr-4">Удалить</button>
        </form>
    </div>
    <table class="bottom">
        <caption style="caption-side: top; text-align: center; color: #000">Список товаров</caption>
        <thead>
        <tr>
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
        @foreach ($order->orders as $ord)
            <tr>
                <td data-label="Фото">
                    @if($ord->item->deleted_at)
                        <img
                            src="{{ Storage::disk('uploads')->url($ord->item->img) }}"
                            class="img-thumbnail"
                            alt="{{ $ord->item->title }}"
                        >
                    @else
                        <a href="{{ route('admin.items.show', $ord->item) }}">
                            <img
                                src="{{ Storage::disk('uploads')->url($ord->item->img) }}"
                                class="img-thumbnail"
                                alt="{{ $ord->item->title }}"
                            >
                        </a>
                    @endif
                </td>
                <td data-label="Название">
                    @if($ord->item->deleted_at)
                        <p>{{ $ord->item->title }}</p>
                    @else
                        <a href="{{ route('admin.items.show', $ord->item) }}">{{ $ord->item->title }}</a>
                    @endif
                </td>
                <td data-label="Артикул">
                    {{ $ord->item->article_number }}
                </td>
                <td data-label="Цена">
                    {{ $ord->item->price }} ₽
                </td>
                <td data-label="Кол-во">
                    {{ $ord->cnt }}
                </td>
                <td data-label="Сумма">{{ number_format($ord->item->price * $ord->cnt, 0, ',', ' ') }} ₽</td>
                <td data-label="Категория">{{ $ord->item->category->title }}</td>
            </tr>
        @endforeach
        <tr class="all_sum">
            <td style="text-align: left"><strong>Всего:</strong></td>
            <td style="text-align: right;" colspan="6">
                <strong>{{ number_format($order->orders->sum(fn($item) => $item->item->price * $item->cnt), 0, ',', ' ') }}
                    ₽</strong>
            </td>
        </tr>
        </tbody>
    </table>
@endsection
