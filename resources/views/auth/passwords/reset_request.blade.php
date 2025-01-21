<!-- resources/views/auth/passwords/reset_request.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Redefinir Senha</h2>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label for="email">Endereço de E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required autofocus>
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Enviar Código 2FA</button>
        </form>
    </div>
@endsection
