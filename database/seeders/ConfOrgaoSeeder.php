<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfOrgaoSeeder extends Seeder
{
    public function run()
    {
        DB::table('conf_orgao')->insert([
            [
                'CONF_ORGAO_ABREVIATURA' => 'MPO',
                'CONF_ORGAO_NOME' => 'Ministério do Planejamento e Orçamento',
                'CONF_ORGAO_ADMIN' => 'Administração Central',
            ],
            // Se houver mais órgãos, adicione-os aqui
        ]);
    }
}
