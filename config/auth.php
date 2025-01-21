<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Define o guard de autenticação padrão e o broker de senhas.
    | Aqui, removemos o uso de variáveis de ambiente para evitar sobrescrições indesejadas.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Define os guards de autenticação da aplicação. O guard `web` está configurado
    | para usar o driver `session`, que suporta métodos como `logout()`.
    |
    | Suportado: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Se você precisar de um guard API, descomente e configure conforme necessário.
        // 'api' => [
        //     'driver' => 'token',
        //     'provider' => 'users',
        //     'hash' => false,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Define como os usuários são recuperados do armazenamento de dados.
    | O provider `users` utiliza o modelo `App\Models\User`.
    |
    | Suportado: "eloquent", "database"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Se preferir usar o driver `database`, descomente e configure:
        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Configurações para a funcionalidade de reset de senhas.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens', // Assegure-se de que essa tabela existe
            'expire' => 60, // Expiração do token em minutos
            'throttle' => 60, // Throttle para novas tentativas
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Define o tempo (em segundos) antes que uma confirmação de senha expire.
    |
    */

    'password_timeout' => 10800, // 3 horas
];
