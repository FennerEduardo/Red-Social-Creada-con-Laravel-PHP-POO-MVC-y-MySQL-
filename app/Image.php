<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Asignar la tabla que el modelo va a modificar
    protected $table = 'images';

    // Relación One to Many - de uno a muchos
    public function comments(){
    	//Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla comentarios
    	return $this->hasMany('App\Comment')->orderBy('id', 'desc'); // El método hasMany es el encargado de realizar las conexiones con las demás entidades
    }

     // Relación One to Many - de uno a muchos
    public function likes(){
    	//Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla de likes
    	return $this->hasMany('App\Like');
    }

     // Relación Many to One - de muchos a uno
    public function user(){
    	//Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla de usuarios
    	return $this->belongsTo('App\User', 'user_id'); // Este método recibe dos parametros el modelo a usar y la llave primaria de dicho modelo.
    }
}
