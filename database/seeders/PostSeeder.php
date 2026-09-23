<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Notícia 1
        Post::create([
            'title' => '5 Dicas essenciais para contratar um bom Eletricista',
            'slug' => Str::slug('5 Dicas essenciais para contratar um bom Eletricista'),
            'content' => '<p>Antes de contratar um profissional para mexer na rede elétrica da sua casa, certifique-se de validar suas referências e verificar se ele possui ferramentas de medição adequadas. Segurança vem sempre em primeiro lugar!</p>'
        ]);

        // Notícia 2
        Post::create([
            'title' => 'Como evitar vazamentos e problemas hidráulicos no inverno',
            'slug' => Str::slug('Como evitar vazamentos e problemas hidráulicos no inverno'),
            'content' => '<p>Pequenas rachaduras podem virar grandes dores de cabeça com as mudanças de temperatura. Monitore o relógio de água e chame um encanador qualificado ao menor sinal de infiltração.</p>'
        ]);

        // Notícia 3
        Post::create([
            'title' => 'Guia prático para escolher a cor ideal da tinta para sua sala',
            'slug' => Str::slug('Guia prático para escolher a cor ideal da tinta para sua sala'),
            'content' => '<p>Cores claras ampliam o ambiente mobile e dão sensação de conforto. Contratar um pintor experiente garante o acabamento uniforme e sem manchas nas paredes do seu imóvel.</p>'
        ]);
    }
}
