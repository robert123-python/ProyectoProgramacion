<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeloImagen extends Model
{
    //
    protected $table = 'modelo_imagenes';
    protected $fillable = ['modelo_id', 'ruta_imagen', 'clase'];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}
