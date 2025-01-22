<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email;
    public $password;
    public $orgao;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|string',
        'orgao' => 'required|string',
    ];

    public function login()
    {
        // Valida os dados de entrada conforme as regras definidas
        $this->validate();

        // Tenta encontrar o usuário com base no email e órgão
        $user = \App\Models\User::where('email', $this->email)
            ->where('orgao', $this->orgao)
            ->first();

        // Verifica se o usuário existe e se a senha está correta
        if ($user && Hash::check($this->password, $user->password)) {
            Auth::login($user, $this->remember);
            return redirect()->intended('/');
        }

        // Se a autenticação falhar, lança uma exceção com mensagem personalizada
        throw ValidationException::withMessages([
            'orgao' => __('failed_with_orgao: O órgão selecionado não corresponde ao cadastrado. Verifique as informações e tente novamente.'),
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
