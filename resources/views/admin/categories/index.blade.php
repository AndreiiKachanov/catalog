@php
    /** @var \App\Models\Admin\Item\Category $category */
    /** @var \Illuminate\Pagination\LengthAwarePaginator $categories */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
        .truncate {
            max-width: 80%;
            white-space: nowrap; /* Текст не переносится */
            overflow: hidden; /* Обрезаем всё за пределами блока */
            text-overflow: ellipsis; /* Добавляем многоточие */
        }
        .row.striped {
            border-top: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            background-color: #f8f8f8;
            color: #000;
            padding: .5rem;
            /*background: #90ea02;*/
        }
        .row.striped:last-child {
            border-bottom: 1px solid #ddd;;
        }

        .row.striped:nth-child(odd){
            /*background: #c6fe6e;*/
        }

        .row.striped:focus, .row.striped:hover{
            /*background: #bcfd55;*/
        }

        @media screen and (max-width: 600px) {
            .row.striped .btn-sm, .btn-group-sm > .btn {
                padding: 0.2rem 0.35rem;
                font-size: 0.6rem;
                line-height: 1.5;
            }
            .row.striped a {
                font-size: 12px;
            }
            .row.striped strong, .orders, .row.striped strong, .sort-header {
                font-size: 13px;
            }
        }
        .orders, .sort-header {
            margin-right: 10px;
            font-weight: bold;
            /*border: 1px solid red;*/
        }
        .order-color {
            color: red;
        }
    </style>
@endsection

@section('content')
    @include('admin.categories._nav')

    <h2 class="text-center">Категории товаров</h2>


    <div class="container mb-2">
        <div class="row pl-2 pr-0 pl-sm-3 pr-sm-3 d-flex align-items-center">
            <div class="col-8 col-md-4 pl-0 pr-0">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-success">Добавить</a>
            </div>
            <div class="col-4 col-md-8 pl-0 pr-0">
                <div class="d-flex flex-row justify-content-end">
                    <div class="orders order-color">Заказы</div>
                    <div class="sort-header ml-1 mr-2">Сортировка</div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        @foreach ($categories as $category)
            <div class="row striped pl-2 pr-0 pl-sm-3 pr-sm-3 d-flex align-items-center">
                <div class="col-8 col-md-4 pl-0 pr-0 d-flex align-items-center">
                    <div class="truncate">
                        <a href="{{ route('admin.categories.show', $category) }}">
                            @for ($i = 0; $i < $category->depth; $i++) &mdash; @endfor
                            {{ $category->title }}
                        </a>&nbsp;

                    </div>
                    <a href="{{ route('admin.categories.show_items', $category) }}">
                        <strong>&nbsp;{{ $category->items_count }}</strong>
                    </a>

                </div>
                <div class="col-4 col-md-8 pl-0 pr-0">
                    <div class="d-flex flex-row justify-content-end align-items-center">
                        <div class="orders order-color">
                            <a href="{{ route('admin.categories.show_orders', $category) }}">
                                {{ $category->order_items_count }}
                            </a>
                        </div>
                        <form method="POST" action="{{ route('admin.categories.first', $category) }}" class="mr-1">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-double-up"></span></button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.up', $category) }}" class="mr-1">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-up"></span></button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.down', $category) }}" class="mr-1">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-down"></span></button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.last', $category) }}" class="mr-1">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary"><span class="fa fa-angle-double-down"></span></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
