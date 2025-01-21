<x-action-section>
    <x-slot name="title">
        {{ __('Inativar Conta') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Inativar permanentemente sua conta.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('Depois que sua conta for inativada, todos os seus recursos e dados ficarão indisponíveis enquanto durar esse processo. Antes de inativar sua conta, baixe todos os dados ou informações que deseja reter.') }}
        </div>

        <div class="mt-5">
            <!-- Botão que chama confirmUserInactivation() -->
            <x-danger-button wire:click="confirmUserInactivation" wire:loading.attr="disabled">
                {{ __('Inativar Conta') }}
            </x-danger-button>
        </div>

        <!-- Inactivate User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserInactivation">
            <x-slot name="title">
                {{ __('Inativar Conta') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Tem certeza de que deseja inativar sua conta? Depois que sua conta for inativada, todos os seus recursos e dados ficarão indisponíveis permanentemente até uma reativação. Digite sua senha para confirmar que deseja inativar permanentemente sua conta.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-inactivate-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input
                        type="password"
                        class="mt-1 block w-3/4"
                        autocomplete="current-password"
                        placeholder="{{ __('Senha') }}"
                        x-ref="password"
                        wire:model="password"
                        wire:keydown.enter="deleteUser"
                    />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button
                    wire:click="$toggle('confirmingUserInactivation')"
                    wire:loading.attr="disabled"
                >
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <!-- Chama deleteUser() no componente -->
                <x-danger-button
                    class="ms-3"
                    wire:click="deleteUser"
                    wire:loading.attr="disabled"
                >
                    {{ __('Inativar Conta') }}
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
