<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TipoAbono;

// Para generar UUID
use Illuminate\Database\Eloquent\Concerns\HasUuids;

// Relaciones
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\HasMany;


class Abono extends Model {

    // generar UUID
    use HasUuids; 

    protected $table = 'abonos'; // indica explícitamente qué tabla representa este modelo

    protected $primaryKey = 'id';     // indica campo al que se asigna UUID generado por HasUUID
    public $incrementing = false;     // hace $primaryKey NO AUTOINCREMENT
    protected $keyType = 'string';    // hace $primaryKey tipo string

    public $timestamps = false;  // indica que la tabla no tiene created_at ni updated_at


    // SEGURIDAD
    // Indica los nombres de los campos de la BD que se pueden rellenar 
    // por el usuario mediante Abono::create($request->all())
    // Impide rellenar otros campos por usuario malintencionado.
    protected $fillable = [
        // 'id',  // omitir id si no se autogenera en Controlador ni lo inserta el usuario
        'fecha', 'abonado', 'edad', 'telefono', 'cuenta_bancaria', 'tipo', 'asiento', 'precio', 
    ];


    // RELACIONES
    // Aplica a nivel de código una relación entre las tablas abonos y tipo_abonos
    public function tipoAbono(): BelongsTo{
        // Un Abono pertenece a un TipoAbono
        // La relación se guarda en la columna 'tipo' de la tabla 'abonos'.
        return $this->belongsTo(TipoAbono::class, 'tipo');
    }
}
