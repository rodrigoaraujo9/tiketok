@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <!-- Name -->
    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <!-- Email -->
    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <!-- Username -->
    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <!-- Phone -->
    <label for="phone">Phone (optional)</label>
    <input id="phone" type="text" name="phone" value="{{ old('phone') }}">
    @if ($errors->has('phone'))
      <span class="error">
          {{ $errors->first('phone') }}
      </span>
    @endif

    <!-- Password -->
    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <!-- Confirm Password -->
    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <!-- Submit -->
    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
