<?php

namespace App\Actions\Jetstream;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected DeletesTeams $deletesTeams)
    {
    }

    /**
     * "Delete" the given user — aqui substituímos a exclusão real pela inativação.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            // Avalie se quer mesmo remover a associação com teams:
            //$this->deleteTeams($user);

            // Exclui foto de perfil para "inativar" (opcional)
            $user->deleteProfilePhoto();

            // Remove tokens de acesso
            $user->tokens->each->delete();

            // Marca o usuário como inativo (em vez de excluir do banco)
            $user->update(['is_active' => false]);
        });
    }

    /**
     * Delete the teams and team associations attached to the user.
     */
    /**protected function deleteTeams(User $user): void
    {
        // Se não quiser remover times, comente o detach e o each(...)
        $user->teams()->detach();

        $user->ownedTeams->each(function (Team $team) {
            $this->deletesTeams->delete($team);
        });
    }*/
}
