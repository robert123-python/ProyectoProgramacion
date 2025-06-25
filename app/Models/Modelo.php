<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    protected $fillable = ['user_id', 'nombre', 'ruta_modelo'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function imagenes()
{
    return $this->hasMany(ModeloImagen::class);
}
}
?>