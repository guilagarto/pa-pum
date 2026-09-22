<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Insira aqui os campos correspondentes à sua tabela de reviews
    protected $fillable = ['job_id', 'rating', 'comment']; 

    // Relacionamento invertido: Uma avaliação pertence a uma vaga (Job)
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
