@php
    /** @var \App\Models\Admin\Item\Category $category */
    /** @var \Illuminate\Database\Eloquent\Collection $categories */
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.subcategories._nav')
{{--    <h2 class="text-center">Выберите категорию</h2>--}}
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Название категории</th>
            <th>Кол-во подкатегорий</th>
        </tr>
        </thead>
        <tbody>

        @foreach ($categories as $category)
            <tr>
                <td><a href="{{ route('admin.subcategories.show', $category) }}">{{ $category->title }}</a></td>
                <td>{{ $category->children_count }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>

    <div class="pagination justify-content-center">
        {{ $categories->links() }}
    </div>
@endsection
