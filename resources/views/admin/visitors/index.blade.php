@php
    /** @var \App\Models\Admin\Cart\Token $token */
    /** @var \Illuminate\Pagination\LengthAwarePaginator $tokens */
@endphp

@extends('layouts.app')
@section('custom_css')
    <style>
        table.bottom {
            font-size: 12px;
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }


        caption {
            padding: 0 !important;
            caption-side: top;
        }

        caption h2 {
            color: #000;
        }

        caption p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        fieldset {
            /*width: max-content;*/
            margin-bottom: 10px;
            padding: 0px 10px 0 10px;
            border: 1px solid #cdcdcd;
        }

        legend {
            padding: 0 10px;
            text-align: center;
            width: auto;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 0;
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

        img {
            vertical-align: middle;
            border-style: none;
            height: auto;
        }

        /*table.bottom tr td:nth-child(2) {*/
        /*    text-align: left;*/
        /*}*/

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

            /*caption {*/
            /*    font-size: 1.3em;*/
            /*}*/

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
    @include('admin.visitors._nav')

    <table class="bottom">
        <caption>
{{--            <h2 class="text-center">Посетители</h2>--}}
{{--            @isset($minDate)--}}
                <fieldset>
                    <legend>
                        <h2>Посетители</h2>
                    </legend>
                    <p>Мобильник - <strong>{{ $countMobileUsers }}</strong></p>
                    <p>Десктоп - <strong>{{ $countDesktopUsers }}</strong></p>
                </fieldset>
{{--            @endisset--}}
            <p>Всего - <strong>{{ $countAllUsers }}</strong></p>
        </caption>

        <thead>
        <tr>
            <th scope="col">Дата первого входа</th>
            <th scope="col">Дата последнего входа</th>
            <th scope="col">Кол-во посещений</th>
            <th scope="col">Робот</th>
            <th scope="col">Ip адрес</th>
            <th scope="col">Город</th>
            <th scope="col">Девайс</th>
            <th scope="col">Версия девайса</th>
            <th scope="col">Платформа</th>
            <th scope="col">Браузер</th>
            <th scope="col">Мобильник</th>
            <th scope="col">Планшет</th>
            <th scope="col">Десктоп</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tokens as $token)
            <tr>
                <td data-label="Дата первого входа"> {{ $token->created_at->format('d.m.Y H:i:s') }}</td>
                <td data-label="Дата последнего входа">
                    {!! is_null($token->last_visit) ? '&nbsp;' : $token->last_visit->format('d.m.Y H:i:s') !!}
                </td>
                <td data-label="Кол-во посещений">
                    {!! is_null($token->visits_count) ? '&nbsp;' : $token->visits_count !!}
                </td>
                <td data-label="Робот">{!! $token->is_robot ? '&#x2705;' : '&nbsp;' !!}</td>
                <td data-label="Ip адрес">
                    {{ filter_var($token->ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? 'ipv6' : $token->ip }}
                </td>
                <td data-label="Город">
                    @if(isset($token->ip_info['flag']))
                        <img style="width: 15px" src="{{ $token->ip_info['flag']['img'] }}" alt="Flag" class="flag-icon">
                    @endif
                    {!! is_null($token->ip_info) ? '&nbsp;' : $token->ip_info['city'] ?? '&nbsp;' !!}
                </td>
                <td data-label="Девайс">
                    {!! is_null($token->device) ? '&nbsp;' : $token->device !!}
                </td>
                <td data-label="Версия девайса">
                    {!! is_null($token->device_version) ? '&nbsp;' : $token->device_version !!}
                </td>
                <td data-label="Платформа">
                    {!! is_null($token->platform) ? '&nbsp;' : $token->platform !!}
                </td>
                <td data-label="Браузер">
                    {!! is_null($token->browser) ? '&nbsp;' : $token->browser !!}
                </td>
                <td data-label="Мобильник">{!! $token->is_mobile ? '&#x2705;' : '&nbsp;' !!}</td>
                <td data-label="Планшет">{!! $token->is_tablet ? '&#x2705;' : '&nbsp;' !!}</td>
                <td data-label="Десктоп">{!! $token->is_desktop ? '&#x2705;' : '&nbsp;' !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="pagination justify-content-center mt-3">
        {{ $tokens->links() }}
    </div>
@endsection
