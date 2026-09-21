<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // Permite que o Laravel grave dados nesses campos do banco
    protected $fillable = ['user_id', 'profile_picture', 'bio', 'rating_cache'];

    // Relacionamento inverso: O perfil pertence a um Usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Um perfil pode ter até 5 fotos no portfólio
    public function portfolioImages()
    {
        return $this->hasMany(PortfolioImage::class);
    }

    // Um perfil pode receber muitas avaliações
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
