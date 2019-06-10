<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Cargar el modelo para los likes
use App\Like;

class LikeController extends Controller
{
    //Método constructor para traer el middleware de autenticación 
	public function __construct()
    {
        $this->middleware('auth');
    }

    //Método para mostrar los likes
    public function index(){
        //Obtener los likes y ordenadorlos por id
        $user = \Auth::user();

        $likes = Like::where('user_id', $user->id)
                        ->orderBy('id', 'desc')
                        ->paginate(5);

        //Retornar vista con las variables a usar
        return view('like.index', [
            'likes' => $likes 
        ]);
    }


    //Método para almacenar likes
    public function like($image_id){
    	//Recoger datos del usuario y de la imagen
    	$user = \Auth::user();


    	// Condición para saber sí el like ya existe y no duplicarlo
    	$isset_like = Like::where('user_id', $user->id)
    						->where('image_id', $image_id)
    						->count();

		if($isset_like == 0){ 
    	// Objeto del modelo like
    	$like = new Like();
    	//Cargo el id del usuario al like
    	$like->user_id = $user->id;
    	$like->image_id = (int)$image_id;

    	//Guardar en la base de datos
    	$like->save();

    	//Retornar un objeto Json para el like
    	return response()->json([
    		'like' => $like
    	]);

    	}else {
    		//Retornar un objeto Json para el like
	    	return response()->json([
	    		'message' => 'El like ya existe'
	    	]);
    	}
    }

    //Método para eliminar Likes
    public function dislike($image_id){
    	//Recoger datos del usuario y de la imagen
    	$user = \Auth::user();

    	// Condición para saber sí el like ya existe y no duplicarlo
    	$like = Like::where('user_id', $user->id)
    						->where('image_id', $image_id)
    						->first();

		if($like){ 
    	
    	//Eliminar el like en la base de datos
    	$like->delete();

    	//Retornar un objeto Json para el like
    	return response()->json([
    		'like' => $like,
    		'message' => 'Has dado dislike correctamente'
    	]);

    	}else {
    		//Retornar un objeto Json para el like
	    	return response()->json([
	    		'message' => 'El like no existe'
	    	]);
    	}
    }

}
