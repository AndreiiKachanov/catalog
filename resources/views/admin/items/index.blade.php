@php
    /** @var \App\Models\Admin\Item\Item $item */
    /** @var \Illuminate\Pagination\LengthAwarePaginator $items */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
        .input-block {
            display: flex;
            flex-wrap: wrap;
        }

        .input-block input[type='search'] {
            flex-basis: calc(100% - 85px);
            margin-right: 10px;
        }

        .input-block input[type='submit'] {
            width: 75px;
        }

        table.category-info {
            font-size: 12px;
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }

        table.category-info tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: .35em;
        }

        table.category-info th,
        table.category-info td {
            padding: .625em;
            text-align: center;
        }

        table.category-info th {
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

        table.category-info tr td:nth-child(2) {
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

            table.category-info tr td:first-child {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
            }

            img {
                max-width: 35% !important;
                vertical-align: middle !important;
                border-style: none !important;
            }


            table.category-info {
                border: 0;
            }

            table.category-info tr td:nth-child(2) {
                text-align: right;
            }

            caption {
                font-size: 1.3em;
                margin: .5em 0 .75em;
            }

            table.category-info thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            table.category-info tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }

            table.category-info td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: 1em;
                text-align: right;
            }

            table.category-info td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }

            table.category-info td:last-child {
                border-bottom: 0;
            }

            .radio-block {
                font-size: 13px;
                justify-content: space-around !important;
            }
        }
    </style>
@endsection

@section('content')
    @include('admin.items._nav')

    <h2 class="text-center">Список товаров</h2>

    <div class="card mb-3">
        <div class="card-header">Поиск товаров</div>
        <div class="card-body p-3">
            {{ Form::open(['method' => 'GET', 'route' => ['admin.items.index']]) }}
                <div class="mb-1 radio-block d-flex justify-content-center">
                    <div class="form-check-inline custom-control custom-radio">
                        {{ Form::radio('radio_search_by', 'title', $radioButton === 'title', ['class'=>'custom-control-input', 'id' => 'title', 'required' => true]) }}
                        {{ Form::label('title', 'Название', ['class' => 'custom-control-label']) }}
                    </div>
                    <div class="form-check-inline custom-control custom-radio">
                        {{ Form::radio('radio_search_by', 'article_number', $radioButton === 'article_number', ['class'=>'custom-control-input', 'id' => 'article_number']) }}
                        {{ Form::label('article_number', 'Артикул', ['class' => 'custom-control-label']) }}
                    </div>
                    <div class="form-check-inline custom-control custom-radio mr-0">
                        {{ Form::radio('radio_search_by', 'category', $radioButton === 'category', ['class'=>'custom-control-input', 'id' => 'category']) }}
                        {{ Form::label('category', 'Категория', ['class' => 'custom-control-label']) }}
                    </div>
                </div>
                @if ($errors->has('radio_search_by'))
                    <div class="d-flex justify-content-center">
                        <span class="invalid-feedback" style="display:block; width: auto">{!! $errors->first('radio_search_by') !!}</span>
                    </div>
                @endif
                <div class="input-block mt-3">
                    {{ Form::search('search_input', old('search_input', $searchInput), [
                            'class' => 'form-control' . setIsValidField('search_input', $errors),
                            'id' => 'search',
                            'required' => false
                        ])
                    }}
                    {{ Form::submit('Поиск', ['class' => 'btn btn-sm btn-primary', 'name' => 'find']) }}
                    @if ($errors->has('search_input'))
                        <span class="invalid-feedback">{!! $errors->first('search_input') !!}</span>
                    @endif
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    @if($items->count() > 0)
    <div class="container mb-2">
        <a href="{{ route('admin.items.create') }}" class="btn btn-sm btn-success">Добавить</a>
    </div>

        <table class="category-info">
            <thead>
            <tr>
                <th scope="col">Фото</th>
                <th scope="col">Название</th>
                <th scope="col">Примечание</th>
                <th scope="col">Артикул</th>
                <th scope="col">Цена закупки</th>
                <th scope="col">{!! formatPrice(\App\Services\SettingsService::getPriceIncrease(), 'Наценка'); !!}</th>
                <th scope="col">Мин. заказ</th>
                <th scope="col">Новый</th>
                <th scope="col">Хит</th>
                <th scope="col">Бест</th>
                <th scope="col">Категория</th>
                <th scope="col">Заказано</th>
                <th scope="col">В корзине</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td data-label="Фото">
                        <a href="{{ route('admin.items.show', $item) }}">
                            <img
                                style="width: 60px;"
                                src="{{ is_null($item->img)
                                            ?  asset('/assets/no-image.png')
                                            : Storage::disk('uploads')->url($item->img)
                                      }}"
                                alt="{{ $item->title }}"
                                title="{{ is_null($item->img) ? 'no-image' : $item->title }}"
                            >
                        </a>
                    </td>
                    <td data-label="Название">
                        <a href="{{ route('admin.items.show', $item) }}">{{ $item->title }}</a>
                    </td>
                    <td data-label="Примечание">
                        {!! $item->note ?? '&nbsp;' !!}
                    </td>
                    <td data-label="Артикул">
                        {!! $item->article_number ?? '&nbsp;' !!}
                    </td>
                    <td data-label="Цена закупки">
                        {{ $item->getRawOriginal('price') }} ₽
                    </td>
                    <td data-label="{{ formatPriceToDataAttribute(\App\Services\SettingsService::getPriceIncrease(), 'Наценка') }}">
                        {{ $item->price }} ₽
                    </td>
                    <td data-label="Мин. заказ">
                        {!! $item->min_order_amount  ?? '&nbsp;' !!}
                    </td>
                    <td data-label="Новый">
                        {!! $item->is_new ? '&#x2705;' : '&nbsp;' !!}
                    </td>
                    <td data-label="Хит">
                        {!! $item->is_hit ? '&#x2705;' : '&nbsp;' !!}
                    </td>
                    <td data-label="Бест">
                        {!! $item->is_bestseller ? '&#x2705;' : '&nbsp;' !!}
                    </td>
                    <td data-label="Категория">
                        @if($item->category)
                            @if($item->category->deleted_at)
                                <span style="color: red;">{{ $item->category->title }} (удалена {{ $item->category->deleted_at->format('d.m.Y') }})</span>
                            @else
                                <a href="{{ route('admin.categories.show', $item->category) }}">
                                    {{ $item->category->title }}
                                </a>
                            @endif
                        @endif
                    </td>
                    <td data-label="Заказано">
                        {{ $item->order_items_count }}
                    </td>
                    <td data-label="В корзине">
                        {{ $item->not_ordered_count }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination justify-content-center mt-3">
            {{ $items->appends(request()->except('page'))->links() }}
        </div>
    @else
        <p class="text-center">Нет данных для отображения.</p>
    @endif
@endsection
