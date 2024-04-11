@extends('layouts.app')

@section('content')
    @include('admin.categories._nav')

    <h2 class="text-center">Создание категории</h2>

    {{ Form::open(['method' => 'post', 'route' => ['admin.categories.store'], 'files' => true]) }}

        <div class="form-group">
            {{ Form::label('title', 'Название категории*', ['class' => 'col-form-label']) }}
            {{ Form::text('title', null, ['class' => 'form-control' . setIsValidField('title', $errors), 'required' => true]) }}
            @if ($errors->has('title'))
                <span class="invalid-feedback">{!! $errors->first('title') !!}</span>
            @endif
        </div>

        <div class="form-group">
            {{ Form::label('parent', 'Родительская категория*', ['class' => 'control-label']) }}
            {{ Form::select('parent', $categories, 0, ['class' => 'form-control' . setIsValidField('parent', $errors), 'id' => 'parent'])}}
            @if ($errors->has('parent'))
                <span class="invalid-feedback">{!! $errors->first('parent') !!}</span>
            @endif
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
            {{ Form::submit('Сохранить', ['class' => 'btn btn-primary']) }}
        </div>
    {{ Form::close() }}
@endsection
