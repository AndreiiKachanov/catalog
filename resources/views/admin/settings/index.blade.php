@php
    /** @var \App\Models\Admin\Setting $setting */
    /** @var \App\Models\Admin\PriceHistory $priceHistory */
    /** @var \Illuminate\Database\Eloquent\Collection $settings */
    /** @var \Illuminate\Database\Eloquent\Collection $priceHistories */
@endphp
@extends('layouts.app')

@section('content')
    @include('admin.settings._nav')
    <h2 class="text-center">Общие настройки</h2>
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if ($settings->isEmpty())
                <h2 style="color: red" class="text-center mt-5">Настройки не заданы!</h2>
            @else
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['method' => 'post', 'route' => ['admin.settings.update'], 'files' => false, 'class' => 'container']) }}
                        @foreach($settings as $setting)
                            <div class="form-group">
                                @php
                                    $propKey = $setting->prop_key;
                                    $isRequired = in_array($propKey, $requiredFieldsArr);
                                    $labelTitleWithStarSymbol = $setting->title . ($isRequired ? '*' : '');
                                @endphp
                                {{ Form::label($propKey, $labelTitleWithStarSymbol, ['class' => 'col-form-label']) }}

                                @php
                                    $numberInputTypes = ['price_increase','min_order_cost'];
                                    $textAreaType = ['custom_text'];
                                    $fieldType = in_array($propKey, $numberInputTypes)
                                    ? 'number'
                                    : (in_array($propKey, $textAreaType) ? 'textarea' : 'text');
                                @endphp
                                {{ Form::{$fieldType}($propKey, old($propKey, $setting->prop_value),
                                        [
                                            'class' => 'form-control' . setIsValidField($propKey, $errors),
                                            'id' => $propKey,
                                            'required' => $isRequired,
                                            in_array($propKey, $textAreaType) && strlen($setting->prop_value) > 100 ? 'rows=8' : 'rows=4'
                                        ])
                                }}
                                @if($setting->prop_key === 'price_regulation' && $priceHistories->count() > 0)
                                    <div>История изменений:</div>
                                    <p>
                                        @foreach($priceHistories as $priceHistory)
                                            <span style="font-size: 13px">{{ $priceHistory->price_updated_at->format('d.m.y') }}</span>
                                              <strong>{{ ($priceHistory->percent > 0 ? '+' . $priceHistory->percent : $priceHistory->percent) . '%' }}</strong>;
                                        @endforeach
                                    </p>
                                @endif
                                @if ($errors->has($propKey))
                                    <span class="invalid-feedback">{!! $errors->first($propKey) !!}</span>
                                @endif
                            </div>
                        @endforeach
                        <div class="form-group mb-0">
                            {{ Form::submit('Сохранить', ['class' => 'btn btn-sm btn-primary'])  }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
