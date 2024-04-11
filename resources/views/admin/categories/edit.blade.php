@php
    /** @var \App\Models\Admin\Item\Category $category */
    //dump($category->parent_id);
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.categories._nav')

    <h2 class="text-center">Редактирование категории</h2>

    {{ Form::open(['method' => 'put', 'route' => ['admin.categories.update', $category], 'files' => true]) }}

        <div class="form-group">
            {{ Form::label('title', 'Название категории*', ['class' => 'col-form-label']) }}
            {{ Form::text('title', old('title', $category->title), ['class' => 'form-control' . setIsValidField('title', $errors), 'required' => true]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">{!! $errors->first('title') !!}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('parent', 'Родительская категория*', ['class' => 'control-label']) }}
            {{ Form::select('parent', $categories, old('parent', $category->parent_id), ['class' => 'form-control' . setIsValidField('parent', $errors), 'id' => 'parent'])}}
            @if ($errors->has('parent'))
                <span class="invalid-feedback">{!! $errors->first('parent') !!}</span>
            @endif
        </div>

        <div class="row">
            <div style="margin: 20px auto;">
                <img src="{{ is_null($category->img) ?  asset('/assets/no-image.png') : Storage::disk('uploads')->url($category->img) }}" alt="{{ $category->title }}">
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('img', 'Фото (jpg, png, jpeg, gif, svg)', ['class' => 'control-label']) }}
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
