<!-- resources/views/auth/passwords/reset.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">{{ __('Redefinir Senha') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Campo de Email -->
                            <div class="form-group">
                                <label for="email">{{ __('Endereço de E-mail') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ $email ?? old('email') }}" required autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Campo de Nova Senha -->
                            <div class="form-group">
                                <label for="password">{{ __('Nova Senha') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Campo de Confirmação de Senha -->
                            <div class="form-group">
                                <label for="password-confirm">{{ __('Confirmar Nova Senha') }}</label>
                                <input id="password-confirm" type="password" class="form-control"
                                       name="password_confirmation" required>
                            </div>

                            <!-- Campo de Código 2FA -->
                            <div class="form-group">
                                <label for="two_factor_code">{{ __('Código de Autenticação de Dois Fatores') }}</label>
                                <input id="two_factor_code" type="text" class="form-control @error('two_factor_code') is-invalid @enderror"
                                       name="two_factor_code" required>

                                @error('two_factor_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Redefinir Senha') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
