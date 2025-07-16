<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importa a classe User do Laravel para autenticação
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // Extende Authenticatable para funcionalidades de autenticação
{
    use HasFactory, Notifiable;

    // Define o nome da tabela se for diferente da convenção plural (users)
    protected $table = 'users'; // O Laravel já espera 'users', mas é bom ser explícito

    // Define a chave primária se for diferente de 'id'
    protected $primaryKey = 'user_id';

    // Indica que a chave primária não é auto-incrementável (se fosse o caso)
    // public $incrementing = false;

    // Define o tipo da chave primária (se não for int)
    // protected $keyType = 'string';

    // Campos que podem ser preenchidos em massa (mass assignable)
    protected $fillable = [
        'email',
        'password_hash', // Usamos password_hash na migração
        'user_type',
        'status',
    ];

    // Campos que devem ser ocultados ao serializar o modelo para arrays/JSON
    protected $hidden = [
        'password_hash', // Oculta a hash da senha
    ];

    // Campos que devem ser "castados" para tipos nativos do PHP
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Sobrescreve o método getAuthPassword para usar 'password_hash'
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Definição de relacionamentos (ex: um User pode ter um perfil de Freelancer ou Company)
    public function freelancer()
    {
        return $this->hasOne(Freelancer::class, 'user_id', 'user_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'user_id', 'user_id');
    }
}
