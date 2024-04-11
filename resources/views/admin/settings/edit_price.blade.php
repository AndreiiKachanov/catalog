@extends('layouts.app')

@section('content')
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.store-price') }}">
        @csrf
        <div class="form-group">
            <label for="price_increase" class="col-form-label">Увеличить цену на %: </label>
            <input
                id="price_increase"
                class="form-control{{ $errors->has('price_increase') ? ' is-invalid' : '' }}"
                name="price_increase"
                value="{{ $priceIncrease ?? old('price_increase') }}" required>
            @if ($errors->has('price_increase'))
                <span class="invalid-feedback"><strong>{{ $errors->first('price_increase') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection
