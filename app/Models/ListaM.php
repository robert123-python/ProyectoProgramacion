<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaM extends Model
{
    protected $table = 'users'; // Asegúrate que tu tabla se llama así en la base de datos
    protected $fillable = ['name', 'email'];
}
