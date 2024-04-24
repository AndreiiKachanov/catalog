@php
    /** @var \App\Models\Admin\Item\Category $category */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
        table.category-info {
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
            font-size: .8em;
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
                font-size: .8em;
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
        }
    </style>
@endsection

@section('content')
    @include('admin.categories._nav')

    <h2 class="text-center mt-3 mb-3">{{ $category->title }}</h2>

    <div class="row mt-5">
        <div class="col-12">
                <table class="category-info">
                    <thead>
                    <tr>
                        <th scope="col">Фото</th>
                        <th scope="col">Название</th>
                        <th scope="col">Кол-во подкатегорий</th>
                        <th scope="col">Товары</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Фото">
                                <img
                                    src="{{ is_null($category->img) ?  asset('/assets/no-image.png') : Storage::disk('uploads')->url($category->img) }}"
                                    class="img-thumbnail"
                                    alt="{{ $category->title }}"
                                >
                            </td>
                            <td data-label="Название">
                                {{ $category->title }}
                            </td>
                            <td data-label="Кол-во подкатегорий">
                                {{ $category->children_count }}
                            </td>
                            <td data-label="Товары">
                                <a href="{{ route('admin.categories.show_items', $category) }}">
                                    {{ $category->items_count }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
        </div>
        <div class="col-12 d-flex justify-content-center mt-3">
            <a href="{{ route('admin.items.create', ['category' => $category]) }}" class="btn btn-sm btn-success pl-3 pr-3">+ Товар</a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary mr-1 ml-3 pl-3 pr-3">Изменить</a>
            {{ Form::open(['method' => 'delete', 'route' => ['admin.categories.destroy', $category], 'class' => 'ml-3']) }}
                {{ Form::submit('Удалить', ['class' => 'btn btn-sm btn-danger pl-3 pr-3'])  }}
            {{ Form::close() }}
        </div>
    </div>
@endsection
