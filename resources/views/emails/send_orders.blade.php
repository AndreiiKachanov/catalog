@php
    /** @var \App\Models\Admin\Cart\Order\Order $contact */
    /** @var \App\Models\Admin\Cart\Order\OrderItem $order */
@endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Заявка с сайта {{ config('app.site_short') }}</title>
    <style>
        body * {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .top table {
            border: 1px solid #dee2e6;
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .top table, .top table, .top tbody, .top tr, .top td {
            box-sizing: border-box;
        }

        .top table tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .top td {
            padding: 0.55rem 0.75rem;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        table.bottom {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }


        table.bottom caption, .top table caption {
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

            table.bottom caption, .top table caption {
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
                font-size: 1em;
                text-align: right;
            }

            table.bottom td::before {
                font-size: 0.9em;
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
</head>
<body>
    <div class="top">
        <table>
            <caption>Заказ № {{ $contact->token->invoice->bill_number }}</caption>
            <tbody>
            <tr>
                <td>Имя</td>
                <td>{{ $contact->name }}</td>
            </tr>
            <tr>
                <td>Номер телефона</td>
                <td>{{ $contact->phone }}</td>
            </tr>
            <tr>
                <td>Город</td>
                <td>{{ $contact->city }}</td>
            </tr>
            <tr>
                <td>Улица</td>
                <td>{{ $contact->street }}</td>
            </tr>
            <tr>
                <td>Дом</td>
                <td>{{ $contact->house_number }}</td>
            </tr>
            <tr>
                <td>Транспортная компания</td>
                <td>{{ $contact->transport_company }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <table class="bottom">
        <caption>Список товаров</caption>
        <thead>
        <tr>
            <th scope="col">Фото</th>
            <th scope="col">Название</th>
            <th scope="col">Артикул</th>
            <th scope="col">Цена</th>
            <th scope="col">Кол-во</th>
            <th scope="col">Сумма</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($contact->orderItems as $order)
            <tr>
                <td data-label="Фото">
                    <img
                        src="{{ Storage::disk('uploads')->url($order->item->img) }}"
                        class="img-thumbnail"
                        alt="{{ $order->item->title }}"
                    >
                </td>
                <td data-label="Название">
                    {{ $order->item->title }}
                </td>
                <td data-label="Артикул">
                    {{ $order->item->article_number }}
                </td>
                <td data-label="Цена">
                    {{ $order->item->price }} руб
                </td>
                <td data-label="Кол-во">
                    {{ $order->cnt }}
                </td>
                <td data-label="Сумма">{{ $order->item->price * $order->cnt }} руб</td>
            </tr>
        @endforeach
        <tr class="all_sum">
            <td style="text-align: left">Всего:</td>
            <td style="text-align: right;" colspan="5">
                {{ $contact->orderItems->sum(fn($item) => $item->item->price * $item->cnt) }} руб
            </td>
        </tr>
        </tbody>
    </table>
</body>
</html>
