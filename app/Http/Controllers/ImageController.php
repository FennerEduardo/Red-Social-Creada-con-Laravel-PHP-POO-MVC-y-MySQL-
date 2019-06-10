<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Usar el Response
use Illuminate\Http\Response;
// Usar el file
use Illuminate\Support\Facades\Storage;
// Usar el file
use Illuminate\Support\Facades\File;
//Usar el modelo de imagenes
use App\Image;
//Usar el modelo de comentarios
use App\Comment;
//Usar el modelo de imagenes
use App\Like;


class ImageController extends Controller
{
	//Método constructor para traer el middleware de autenticación 
	public function __construct()
    {
        $this->middleware('auth');
    }

    //Método para crear imagénes 
    public function create(){

    	//Retornar una vista para la creración de imagénes
    	return view('image.create');
    }

    //Método para guardar las imagenes 
    public function save(Request $request){
    	//Validación de los datos del formulario
    	$validate = $this->validate($request, [
    		'description' => 'required',
    		'image_path'  => 'required|image'
    	]);


    	//Obteniendo el archivo de imagen y los datos
    	$image_path = $request->file('image_path');
    	//obteniendo la descripcion de la imagen
    	$description = $request->input('description');

    	//Asignar valores al objeto de image
    	$user = \Auth::user();
    	$image = new Image();
    	$image->user_id = $user->id;
    	
    	$image->description = $description;

    	//Subir el fichero
    	if ($image_path) {
    		$image_path_name = time().$image_path->getClientOriginalName();
    		Storage::disk('images')->put($image_path_name, File::get($image_path));

    		$image->image_path = $image_path_name;
    	}

    	//almacenar el imagen en la base de datos
    	$image->save();

    	//Retornar y redireecionar
    	return redirect()->route('home')->with([
    		'message' => 'La foto ha sido subida correctamente'
    	]);
    	
    }

    //Método para obtener las imagenes almacenadas en el disco 
    public function getImage($filename){
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    //Método para mostrar los detalles de la imagen
    public function detail($id){
        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    //Método para eliminar o borrar una imagen
    public function delete($id){
        //Obtener el  usuario activo
        $user = \Auth::user();
        //Obtener el id de la imagen que se va a borrar
        $image = Image::find($id);
        //Obteniendo los comentarios de la imagen
        $comments = Comment::where('image_id', $id)->get();
    }
}
