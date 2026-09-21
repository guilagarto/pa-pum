<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // Permite a gravação dos dados nos campos correspondentes do banco
    protected $fillable = ['user_id', 'title', 'description', 'category', 'is_premium', 'premium_until', 'status'];

    // Relacionamento: A vaga pertence a um Usuário (Contratante)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento: A vaga pode ter uma avaliação vinculada
    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
