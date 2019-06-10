<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role','name', 'email', 'surname', 'nick', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relación One to Many - de uno a muchos
    public function images(){
        //Retornar el modelo que con el va a interactuar mediante la llave foranea, en este caso la tabla comentarios
        return $this->hasMany('App\Image'); // El método hasMany es el encargado de realizar las conexiones con las demás entidades
    }
}
