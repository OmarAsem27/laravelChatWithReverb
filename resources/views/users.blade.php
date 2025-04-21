@extends('layouts.app')


@section('content')
    <div class="container">
        <h1>Online Users</h1>
        <ul class="list-group mt-3">

            @foreach ($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">

                    <a href="{{ route('chat', $user->id) }}">{{ $user->name }} </a>
                    <span class="badge {{ $user->isOnline() ? 'bg-success' : 'bg-secondary' }}">
                        {{ $user->isOnline() ? 'Online' : 'Offline' }}
                    </span>

                </li>
            @endforeach

        </ul>
    </div>
@endsection
