@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <!-- Name -->
    <label for="name"style="font-weight:500">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" style="font-weight:200" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <!-- Email -->
    <label for="email" style="font-weight:500">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" style="font-weight:200" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <!-- Username -->
    <label for="username" style="font-weight:500">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}"  style="font-weight:200" required>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <!-- Phone -->
    <label for="phone" style="font-weight:500">Phone (optional)</label>
    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" style="font-weight:200">
    @if ($errors->has('phone'))
      <span class="error">
          {{ $errors->first('phone') }}
      </span>
    @endif

    <!-- Password -->
    <label for="password" style="font-weight:500">Password</label>
    <input id="password" type="password" name="password" style="font-weight:200" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <!-- Confirm Password -->
    <label for="password-confirm" style="font-weight:500">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" style="font-weight:200" required>

    <!-- Submit -->
    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
