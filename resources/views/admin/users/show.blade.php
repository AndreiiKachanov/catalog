@php
    /** @var \App\Models\User\User $user */
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <div class="d-flex flex-row mb-3">
        @if ($user->isWait())
            <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="mr-1">
                @csrf
                <button class="btn btn-success">Verify</button>
            </form>
        @endif

    </div>

    <table class="table table-bordered table-striped">
        <tbody>
        <tr>
            <th>ID</th><td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Name</th><td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th><td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @if($user->isWait())
                    <span class="badge badge-secondary">Waiting</span>
                @endif
                @if ($user->isActive())
                    <span class="badge badge-primary">Active</span>
                @endif
            </td>
        </tr>
        <tr>
            <th>Role</th>
            <td>
                @if ($user->isAdmin())
                    <span class="badge badge-danger">Admin</span>
                @elseif ($user->isUser())
                    <span class="badge badge-secondary">User</span>
                @else
                    <span class="badge badge-success">Moderator</span>
                @endif
            </td>
        </tr>
        <tbody>
        </tbody>
    </table>
@endsection
