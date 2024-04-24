@php
    /** @var \App\Models\Admin\Item\Item $item */
    /** @var \Illuminate\Database\Eloquent\Collection $categories */
@endphp

@extends('layouts.app')

@section('custom_css')
    <style>
        .form-check {
            margin-bottom: 1rem;
        }

        .form-check .custom-checkbox {
            margin-right: 15px;
        }

        .form-check .custom-control-input.is-invalid:checked ~ .custom-control-label::before {
            border-color: transparent !important;
            color: transparent !important;
            background-color: #007bff !important;
            outline: none !important;
        }

        .form-check .custom-control-input.is-invalid:focus ~ .custom-control-label::before {
            box-shadow: none !important;
        }

        span.invalid-feedback {
            font-size: .95rem;
        }
    </style>
@endsection

@section('content')
    @include('admin.items._nav')

    <h2 class="text-center">Редактирование товара</h2>
    {{ Form::open(['method' => 'put', 'route' => ['admin.items.update', $item], 'files' => true]) }}
    <div class="form-group">
        {{ Form::label('title', 'Название*', ['class' => 'col-form-label']) }}
        {{ Form::text('title', old('title', $item->title), ['class' => 'form-control' . setIsValidField('title', $errors), 'required' => true]) }}
        @if ($errors->has('title'))
            <span class="invalid-feedback">{!! $errors->first('title') !!}</span>
        @endif
    </div>

    <div class="form-group">
        {{ Form::label('note', 'Примечание', ['class' => 'col-form-label']) }}
        {{ Form::text('note', old('note', $item->note), ['class' => 'form-control' . setIsValidField('note', $errors), 'required' => false]) }}
        @if ($errors->has('note'))
            <span class="invalid-feedback">{!! $errors->first('note') !!}</span>
        @endif
    </div>

        <div class="form-group">
            {{ Form::label('article_number', 'Артикул*', ['class' => 'col-form-label']) }}
            {{ Form::text('article_number', old('article_number', $item->article_number), ['class' => 'form-control' . setIsValidField('article_number', $errors), 'required' => true]) }}
            @if ($errors->has('article_number'))
                <span class="invalid-feedback">{!! $errors->first('article_number') !!}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('price', 'Цена закупки (₽)*', ['class' => 'col-form-label']) }}
            {{ Form::number('price', old('price', $item->getRawOriginal('price')), ['class' => 'form-control' . setIsValidField('price', $errors), 'required' => true]) }}
            @if ($errors->has('price'))
                <span class="invalid-feedback">{!! $errors->first('price') !!}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('min_order_amount', 'Мин. заказ', ['class' => 'col-form-label']) }}
            {{ Form::number('min_order_amount', old('min_order_amount', $item->min_order_amount), ['class' => 'form-control' . setIsValidField('min_order_amount', $errors), 'required' => false, 'min' => 1, 'max' => 1000000000]) }}
            @if ($errors->has('min_order_amount'))
                <span class="invalid-feedback">{!! $errors->first('min_order_amount') !!}</span>
            @endif
        </div>

        <div class="form-check d-flex justify-content-start">
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox('is_new', null, old('is_new', $item->is_new), ['class' => 'custom-control-input' . setIsValidField('is_new', $errors), 'id' => 'is_new']) }}
                {{ Form::label('is_new', 'Новый', ['class' => 'custom-control-label']) }}
                @if ($errors->has('is_new'))
                    <span class="invalid-feedback">{!! $errors->first('is_new') !!}</span>
                @endif
            </div>
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox('is_hit', null, old('is_new', $item->is_hit), ['class' => 'custom-control-input' . setIsValidField('is_hit', $errors), 'id' => 'is_hit']) }}
                {{ Form::label('is_hit', 'Хит', ['class' => 'custom-control-label']) }}
                @if ($errors->has('is_hit'))
                    <span class="invalid-feedback">{!! $errors->first('is_hit') !!}</span>
                @endif
            </div>
            <div class="custom-control custom-checkbox">
                {{ Form::checkbox('is_bestseller', null, old('is_new', $item->is_bestseller), ['class' => 'custom-control-input' . setIsValidField('is_bestseller', $errors), 'id' => 'is_bestseller']) }}
                {{ Form::label('is_bestseller', 'Бестселлер', ['class' => 'custom-control-label']) }}
                @if ($errors->has('is_bestseller'))
                    <span class="invalid-feedback">{!! $errors->first('is_bestseller') !!}</span>
                @endif
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('category', 'Категория*', ['class' => 'control-label']) }}
            {{ Form::select('category', $categories, old('category', $item->category_id), ['class' => 'form-control' . setIsValidField('category', $errors), 'id' => 'category'])}}
            @if ($errors->has('category'))
                <span class="invalid-feedback">{!! $errors->first('category') !!}</span>
            @endif
        </div>

        <div class="row">
            <div style="margin: 20px auto;">
                <img src="{{ Storage::disk('uploads')->url($item->img) }}" alt="" width="300" height="350">
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('img', 'Фото (jpg, png, jpeg, gif, svg)*', ['class' => 'control-label']) }}
            {{ Form::file('img', [
                                    'class' => 'form-control' . ($errors->has('img') ? ' is-invalid' : ''),
                                    'id' => 'img',
                                    'required' => false,
                                    'accept' => '.jpg, .png, .jpeg, .gif, .svg'
                                  ]
                          )
            }}
            @if ($errors->has('img'))
                <span class="invalid-feedback">{!! $errors->first('img') !!}</span>
            @endif
        </div>

    <div class="form-group">
        {{ Form::submit('Сохранить', ['class' => 'btn btn-primary'])  }}
    </div>
    {{ Form::close() }}
@endsection
