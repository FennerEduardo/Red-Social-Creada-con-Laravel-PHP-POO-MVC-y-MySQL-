<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Cargar elmodelo de comentarios
use App\Comment;
use Illuminate\Support\Facades\Mail;
use App\Mail\PruebaMail;


class CommentController extends Controller
{
    //Método constructor para traer el middleware de autenticación 
	public function __construct()
    {
        $this->middleware('auth');
    }

    //Método para almacenar los comentarios
    public function save(Request $request){

    	//Validar datos del formulario
    	$validate = $this->validate($request, [
    		'image_id' => 'integer|required',
    		'content'  => 'string|required'
    	]);

    	//Recoger datos del formulario
        $user = \Auth::user();
    	$image_id = $request->input('image_id');
    	$content = $request->input('content');

        //Asignar valores al nuevo objeto de comentarios para guardarlo
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //Guardar los comentarios
        $comment->save();
        
        //Retorno y redirección 
        return redirect()->route('image.detail', ['id' => $image_id])
                         ->with([
                            'message' => 'Has publicado tu comentario correctamente!!'
                         ]);


    }

    //Método para eliminar comentarios
    public function delete($id){
        //Conseguir los datos del usuarios logueado
        $user = \Auth::user();

        //Conseguir el objeto del comentario
        $comment = Comment::find($id);

        //Comprobar sí soy el dueño del comentario o la publicación
        if($user && ($comment->user_id == $user->id || $comment->image->user_id == $user->id)){
            $comment->delete(); //Elimina el comentario

             //Retorno y redirección 
        return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                            'message' => 'Comentario Eliminado correctamente!!'
                         ]);
        } else{

             //Retorno y redirección 
        return redirect()->route('image.detail', ['id' => $comment->image->id])
                         ->with([
                            'message' => 'El comentario no se ha eliminado'
                         ]);
        }


    }
}
