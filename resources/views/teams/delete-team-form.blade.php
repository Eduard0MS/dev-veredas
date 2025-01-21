<x-action-section>
    <x-slot name="title">
        {{ __('Excluir Projeto') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Exclua permanentemente este projeto.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Depois que um Projeto for excluída, todos os seus recursos e dados serão excluídos permanentemente. Antes de excluir este Projeto, baixe quaisquer dados ou informações sobre ele que você deseja manter.') }}
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('Excluir Projeto') }}
            </x-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('Excluir Projeto') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Tem certeza de que deseja excluir este Projeto? Depois que uma equipe for excluída, todos os seus recursos e dados serão excluídos permanentemente.') }}
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('Excluir Projeto') }}
                </x-danger-button>
            </x-slot>
        </x-confirmation-modal>
    </x-slot>
</x-action-section>
