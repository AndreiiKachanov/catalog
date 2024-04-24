@php
    /** @var \App\Models\Admin\Item\Item $item */
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.items._nav')

    <h2 class="text-center mt-3 mb-3">{{ $item->title }}</h2>

    <div class="row">
        <div style="margin: 10px auto;">
            <img src="{{ Storage::disk('uploads')->url($item->img) }}" alt="{{ $item->title }}">
        </div>
    </div>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th>Название</th><td>{{ $item->title }}</td>
            </tr>
            <tr>
                <th>Примечание</th><td>{{ $item->note }}</td>
            </tr>
            <tr>
                <th>Артикул</th><td>{{ $item->article_number }}</td>
            </tr>
            <tr>
                <th>Цена закупки</th>
                <td>{{ $item->getRawOriginal('price') }} &#8381</td>
            </tr>
            <tr>
                <th>{!! formatPrice(\App\Services\SettingsService::getPriceIncrease(), 'Наценка'); !!}</th>
                <td>{{ $item->price }} &#8381</td>
            </tr>
            <tr>
                <th>Мин. заказ</th><td> {{ $item->min_order_amount }}</td>
            </tr>
            <tr>
                <th>Новый</th><td>{!! $item->is_new ? '&#x2705;' : '&nbsp;' !!}</td>
            </tr>
            <tr>
                <th>Хит</th><td>{!! $item->is_hit ? '&#x2705;' : '&nbsp;' !!}</td>
            </tr>
            <tr>
                <th>Бестселлер</th><td>{!! $item->is_bestseller ? '&#x2705;' : '&nbsp;' !!}</td>
            </tr>
            <tr>
                <th>Категория</th>
                <td>
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
            </tr>
        <tbody>
        </tbody>
    </table>
    <div class="col-12 d-flex justify-content-center mt-3">
        <a href="{{ route('admin.items.create', ['category' => $item->category]) }}" class="btn btn-sm btn-success pl-3 pr-3">+ Товар</a>
        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-sm btn-primary ml-3 mr-1 pl-3 pr-3">Изменить</a>
        {{ Form::open(['method' => 'delete', 'route' => ['admin.items.destroy', $item], 'class' => 'ml-3', 'id' => 'delete-form']) }}
            {{ Form::submit('Удалить', ['class' => 'btn btn-sm btn-danger pl-3 pr-3'])  }}
        {{ Form::close() }}
    </div>
    <!-- Модальное окно для подтверждения удаления -->
    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Подтверждение удаления</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modal-text"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger" id="delete-button">Удалить</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function() {
            $('#delete-form').on('submit', function (e) {
                e.preventDefault();
                let form = $(this);
                let url = '{{ route('admin.items.check-before-remove', ['item' => $item->id ]) }}';
                axios.get(url)
                    .then(function (response) {
                        let notOrderedCount = response.data.not_ordered_count;
                        if (notOrderedCount > 0) {
                            let langChoice = '{{ Lang::choice('пользователь добавил|пользователя добавили|пользователей добавили', $item->not_ordered_count) }}';
                            let modalText = `<strong>${notOrderedCount}</strong> ${langChoice} данный товар в корзину. <br>
                            Вы уверены, что хотите удалить?`;

                            $('#modal-text').html(modalText);
                            $('#delete-modal').modal('show');
                            $('#delete-button').on('click', function () {
                                // Если пользователь согласен, отправляем форму для удаления
                                form.unbind('submit').submit();
                            });
                        } else {
                            form.unbind('submit').submit();
                        }
                    })
                    .catch(function (error) {
                        console.log('Произошла ошибка при выполнении запроса:', error);
                    });
            });
        });
    </script>
@endsection
