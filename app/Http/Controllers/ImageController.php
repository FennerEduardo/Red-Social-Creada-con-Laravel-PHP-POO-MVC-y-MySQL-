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
//Calendario
use Spatie\GoogleCalendar\Event;
//Carbon para las fechas
use Carbon\Carbon;

class ImageController extends Controller {

    //Método constructor para traer el middleware de autenticación 
    public function __construct() {
        $this->middleware('auth');
    }

    //Método para crear imagénes 
    public function create() {

        //Retornar una vista para la creración de imagénes
        return view('image.create');
    }

    //Método para guardar las imagenes 
    public function save(Request $request) {
        //Validación de los datos del formulario
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required|image'
        ]);

        $evento = new Event;
       
        $evento->startDateTime = Carbon::now();
        $evento->endDateTime = Carbon::now()->addDay();
        $evento->location = 'Bogotá';



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
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));

            $image->image_path = $image_path_name;
        }
        $evento->name = $image_path_name;
        //almacenar el imagen en la base de datos
        $image->save();
        $evento->save();

        //Retornar y redireecionar
        return redirect()->route('home')->with([
                    'message' => 'La foto ha sido subida correctamente'
        ]);
    }

    //Método para obtener las imagenes almacenadas en el disco 
    public function getImage($filename) {
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    //Método para mostrar los detalles de la imagen
    public function detail($id) {
        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    //Método para eliminar o borrar una imagen
    public function delete($id) {
        //Obtener el  usuario activo
        $user = \Auth::user();
        //Obtener el id de la imagen que se va a borrar
        $image = Image::find($id);
        //Obteniendo los comentarios de la imagen
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id', $id)->get();

        //Condición para eliminar todos los datos de la imagen
        if ($user && $image && $image->user->id == $user->id) {

            //Eliminar Comentarios
            if ($comments && count($comments) >= 1) {
                foreach ($comments as $comment) {
                    $comment->delete();
                }
            }
            //Eliminar Likes
            if ($likes && count($likes) >= 1) {
                foreach ($likes as $like) {
                    $like->delete();
                }
            }
            // Eliminar ficheros de imagen
            Storage::disk('images')->delete($image->image_path);

            // Eliminar registro de imagen en la BD
            $image->delete();
            $message = array('message' => 'La imagen se eliminó correctamente');
        } else {
            $message = array('message' => 'La imagen no se ha borrado');
        }
        //Redirección 
        return redirect()->route('home')->with($message);
    }

    //Método para Editar y o actualizar una imagen
    public function edit($id) {

        //Obtener el usuario autenticado
        $user = \Auth::user();
        //Obtener la imagen 
        $image = Image::find($id);

        //Coprobando que la imagen existe y que le corresponde al usuario registrado
        if ($user && $image && $image->user->id == $user->id) {
            //Retornar vista para la edición de la imagen
            return view('image.edit', [
                'image' => $image
            ]);
        } else {
            return redirect()->route('home');
        }
    }

    //Método para recibir los datos del formulario de edición y guardarlos en la base de datos
    public function update(Request $request) {

        //Validación de los datos del formulario
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'image'
        ]);

        //REcibiendo el id de la imagen
        $image_id = $request->input('image_id');
        //Recibiendo el path de la imagen
        $image_path = $request->file('image_path');
        //Recibiendo la description de la imagen
        $description = $request->input('description');

        //Conseguir objeto image
        $image = Image::find($image_id);
        //Subir nueva descripción
        $image->description = $description;

        //Subir el fichero
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));

            $image->image_path = $image_path_name;
        }

        //Actualizar registro
        $image->update();

        //Redirección a la nueva imagen
        return redirect()->route('image.detail', ['id' => $image_id])
                        ->with(['message' => 'Imagen Actualizada con éxito']);
    }

}
