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
    <title>Заявка с сайта catalog.loc</title>
    <style>
        body * {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            border-collapse: collapse;
        }

        table td, th {
            border: 1px solid #000;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<div style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">У вас новый заказ
    № {{ $contact->token->invoice->bill_number }}:
</div>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Имя: {{ $contact->name }}</p>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Телефон: {{ $contact->phone }}</p>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Город: {{ $contact->city }}</p>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Улица: {{ $contact->street }}</p>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Номер дома: {{ $contact->house_number }}</p>
<p style="font-size: 14px; font-family: 'Helvetica', 'Arial', sans-serif;">Транспортная
    компания: {{ $contact->transport_company }}</p>

<div class="table-responsive">
    <table class="table">
        <tr>
            <th style="text-align: left;">Продукт</th>
            <th>Артикул</th>
            <th>Цена</th>
            <th>Кол-во</th>
            <th>Сумма</th>
        </tr>
        <tbody>
        @foreach ($contact->orders as $ord)
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
                {{ $contact->orders->sum(fn($item) => $item->item->price * $item->cnt) }} ₽
            </td>
        </tr>
        </tbody>
    </table>
</div>

</body>
</html>
