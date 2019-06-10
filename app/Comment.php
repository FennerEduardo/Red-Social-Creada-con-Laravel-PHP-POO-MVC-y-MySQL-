<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
        // Asignar la tabla que el modelo va a modificar
    protected $table = 'comments';

      // Relación Many to One - de muchos a uno
    public function user(){
    	//Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla de usuarios
    	return $this->belongsTo('App\User', 'user_id'); // Este método recibe dos parametros el modelo a usar y la llave primaria de dicho modelo.
    }

      // Relación Many to One - de muchos a uno
    public function image(){
    	//Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla de imagenes
    	return $this->belongsTo('App\Image', 'image_id'); // Este método recibe dos parametros el modelo a usar y la llave primaria de dicho modelo.
    }
}
