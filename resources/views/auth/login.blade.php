@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="login" style="font-weight:500">E-mail or Username</label>
    <input id="login" type="text" name="login" value="{{ old('login') }}" style="font-weight:100" required autofocus>
    @if ($errors->has('login'))
        <span class="error">
            {{ $errors->first('login') }}
        </span>
    @endif

    <label for="password" style="font-weight:500">Password</label>
    <input id="password" type="password" name="password" style="font-weight:100"  required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif
</form>
@endsection
