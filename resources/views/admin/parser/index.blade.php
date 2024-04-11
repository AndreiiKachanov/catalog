@extends('layouts.app')

@section('content')
    @include('admin.parser._nav')

    <div class="currencies">
        <div class="card mb-3">
            <div class="card-header">Vk parser</div>
            <div class="card-body">

                <parser-component
                        route_parse="{{route('admin.start_parse')}}"
                        :user="{{Auth::user()}}"
                >
                </parser-component>

{{--                <currencies-table :user="{{Auth::user()}}"></currencies-table>--}}
            </div>
        </div>
    </div>

@endsection
