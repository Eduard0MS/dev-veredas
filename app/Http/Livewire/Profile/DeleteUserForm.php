<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Livewire\Component;

class DeleteUserForm extends Component
{
    /**
     * Controla o modal de inativação.
     */
    public bool $confirmingUserInactivation = false;

    /**
     * Armazena a senha do usuário.
     */
    public string $password = '';

    /**
     * Exibe o modal de inativação.
     */
    public function confirmUserInactivation(): void
    {
        $this->resetErrorBag();
        $this->password = '';

        $this->dispatch('confirming-delete-user');

        $this->confirmingUserInactivation = true;
    }

    /**
     * Inativa o usuário atual (chamando a Action customizada).
     */
    public function deleteUser()
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        // Pega a Action que inativa ao invés de excluir
        $deleter = app(DeletesUsers::class);
        $deleter->delete(Auth::user()->fresh());

        Auth::logout();

        return redirect('/');
    }

    /**
     * Renderiza a view (em resources/views/profile/delete-user-form.blade.php)
     */
    public function render()
    {
        return view('profile.delete-user-form');
    }
}
