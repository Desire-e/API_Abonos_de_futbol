<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Sanctum\HasApiTokens;


class Usuario extends Authenticatable {
    use HasApiTokens, HasUuids;
    // HasApiTokens habilita:
    // - Crear tokens (createToken())
    // - Usar autenticación con Sanctum
    
    protected $table = "usuarios";

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['username', 'password'];
    
    public $timestamps = false;

    // SEGURIDAD
    // No afecta a la base de datos, solo a la visibilidad de datos.
    // Evita que password aparezca cuando Laravel:
    // - convierte el usuario a array
    // - lo devuelve como JSON
    protected $hidden = ['password'];
}
