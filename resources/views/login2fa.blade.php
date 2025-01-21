<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <h2 class="text-xl font-semibold mb-4">
            Autenticação em 2 Fatores
        </h2>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            <div class="mt-4">
                <x-label for="one_time_password" value="Código 2FA" />
                <x-input id="one_time_password" class="block mt-1 w-full"
                         type="text"
                         name="one_time_password"
                         required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Verificar
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
