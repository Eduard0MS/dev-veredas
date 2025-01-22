<!-- resources/views/auth/passwords/reset_request.blade.php -->

<x-guest-layout>
    <x-jet-authentication-card-logo>
        <x-slot name="logo">
            <!-- Se quiser, coloque aqui um logo, por exemplo, um componente <x-jet-authentication-card-logo /> -->
        </x-slot>

        <!-- Validação de erros -->
        <x-jet-validation-errors class="mb-4" />

        <!-- Mensagem de status (caso exista) -->
        @if (session('status'))
            <div class="mb-4 text-sm font-medium text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <!-- Formulário -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mt-4">
                <x-jet-label for="email" value="Endereço de E-mail" />
                <x-jet-input id="email" class="block mt-1 w-full"
                             type="email"
                             name="email"
                             :value="old('email')"
                             required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    Enviar Código 2FA
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card-logo>
</x-guest-layout>
