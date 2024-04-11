@extends('layouts.app')

@section('content')
    @include('admin.categories._nav')

    <form method="POST" action="{{ route('admin.subcategories.store', ['category' => $category]) }}" enctype="multipart/form-data">
        @csrf
        <h3 class="text-center mb-3 mt-3">Создание подкатегории</h3>
        <div class="form-group">
            <label for="title" class="col-form-label">Название</label>
            <input id="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}" required>
            @if ($errors->has('title'))
                <span class="invalid-feedback"><strong>{{ $errors->first('title') }}</strong></span>
            @endif
        </div>
        <div class="form-group">
            <label for="img" class="col-form-label">Фото (jpg,png,jpeg,gif,svg)</label>
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
