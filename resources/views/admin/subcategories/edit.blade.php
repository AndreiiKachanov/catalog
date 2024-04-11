@php
    /** @var \App\Models\Admin\Item\Category $category */
//    dump($categoriesToView);
//dd($category);
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.categories._nav')

    <form method="POST" action="{{ route('admin.subcategories.update', $category) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            {{ Form::label('parent_id', 'Родительская категория*', ['class' => 'control-label']) }}
            {{ Form::select('parent_id', $categoriesToView, $category->parent_id, ['class' => 'form-control' ]) }}
        </div>

        <div class="form-group">
            <label for="title" class="col-form-label">Название</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title', $category->title) }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>

        <div class="row">
            <div style="margin: 20px auto;">
                <img src="{{ is_null($category->img) ?  asset('/assets/no-image.png') : Storage::disk('uploads')->url($category->img) }}" alt="{{ $category->title }}">
            </div>
        </div>
        <div class="form-group">
            <label for="img" class="col-form-label">Изображение (jpg,png,jpeg,gif,svg)</label>
            <input type="file" name="img" class="form-control{{ $errors->has('img') ? ' is-invalid' : '' }}" id="img">
            @if ($errors->has('img'))
                <span class="invalid-feedback"><strong>{{ $errors->first('img') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
