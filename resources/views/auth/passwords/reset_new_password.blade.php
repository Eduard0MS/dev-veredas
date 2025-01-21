@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Definir Nova Senha</h2>
        <form method="POST" action="{{ route('password.reset.update') }}">
            @csrf
            <div class="form-group">
                <label for="password">Nova Senha</label>
                <input type="password" name="password" id="password" class="form-control" required autofocus>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                @error('password_confirmation')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Redefinir Senha</button>
        </form>
    </div>
@endsection
