<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        /**
     * Relacionamento: O usuário possui um único perfil no Pá-pum.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relacionamento: O usuário pode publicar vários anúncios de serviço.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
        /**
     * Histórico 1: Serviços que este usuário oferece ou realizou como PRESTADOR.
     */
    public function servicesOffered()
    {
        return $this->hasMany(Job::class, 'user_id');
    }

    /**
     * Histórico 2: Serviços que este usuário buscou e abriu chat como CONTRATANTE.
     */
    public function servicesContracted()
    {
        return $this->hasMany(Job::class, 'contractor_id');
    }


}
