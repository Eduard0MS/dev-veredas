<?php
// app/Http/Livewire/ManageUsers.php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManageUsers extends Component
{
    public $users;
    public $selectedUser;
    public $password;

    public function mount()
    {
        $this->users = User::all();
    }

    public function deactivateUser($userId)
    {
        $user = User::find($userId);

        // Prevenir que o admin desative a própria conta
        if ($user->id === Auth::id()) {
            session()->flash('error', 'Administradores não podem desativar suas próprias contas.');
            return;
        }

        $user->is_active = 0;
        $user->save();

        session()->flash('success', 'Conta desativada com sucesso.');
        $this->mount(); // Atualiza a lista de usuários
    }

    public function reactivateUser($userId)
    {
        $currentUser = Auth::user();
        $user = User::find($userId);

        // Verifica se o usuário atual é admin
        if ($currentUser->usertype !== 'admin') {
            session()->flash('error', 'Somente administradores podem reativar contas.');
            return;
        }

        $user->is_active = 1;
        $user->save();

        session()->flash('success', 'Conta reativada com sucesso.');
        $this->mount(); // Atualiza a lista de usuários
    }

    public function render()
    {
        return view('livewire.manage-users');
    }
}
