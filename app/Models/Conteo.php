<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conteo extends Model
{
    protected $fillable = [
        'user_id',
        'modelo_id',
        'ruta_imagen',
        'conteo_automatico',
        'conteo_manual'
    ];

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }
}