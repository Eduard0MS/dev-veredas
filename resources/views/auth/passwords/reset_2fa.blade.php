@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Verificação de Autenticação de Dois Fatores</h2>
        <form method="POST" action="{{ route('password.reset.verify2fa') }}">
            @csrf
            <div class="form-group">
                <label for="2fa_code">Código 2FA</label>
                <input type="text" name="2fa_code" id="2fa_code" class="form-control" required autofocus>
                @error('2fa_code')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Verificar</button>
        </form>
    </div>
@endsection
