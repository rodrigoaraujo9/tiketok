@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Notificações</h1>
    @if ($notifications->isEmpty())
        <p>Sem notificações no momento.</p>
    @else
        <ul class="list-group">
            @foreach ($notifications as $notification)
                <li class="list-group-item {{ $notification['is_read'] ? '' : 'font-weight-bold' }}">
                    <a href="{{ $notification['url'] }}" class="text-decoration-none">
                        {{ $notification['message'] }}
                    </a>
                    <span class="text-muted float-right">{{ $notification['created_at']->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
