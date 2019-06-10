<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Usar método para las respuestas Http
use Illuminate\Http\Response;
//Usar los dicos virtuales de almacenamiento de archivos.
use Illuminate\Support\Facades\Storage;
//Usar los objetos para los ficheros
use Illuminate\Support\Facades\File;
// Usar el modelo de usuario
use App\User;

class UserController extends Controller
{

	//Método constructor para traer el middleware de autenticación 
	public function __construct()
    {
        $this->middleware('auth');
    }
    //Método para la configuración de usuarios
    public function config(){
    	//Retornar la vista
    	return view('user.config');
    }

    //Método para actualizar los registros de usuarios
    public function update(Request $request){

    	//Variable del usuario objeto completo identificado
    	$user = \Auth::user();
    	//Id del usuario 
    	$id = $user->id;
    	//Validación de los datos del formulario
    	$validate = $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:users,nick,'.$id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            ]);

    	//Recoger datos del formulario de configuración de usuarios
    	
    	$name = $request->input('name');
    	$surname = $request->input('surname');
    	$nick = $request->input('nick');
    	$email = $request->input('email');

    	//Asignar nuevos valores al objeto de usuario
    	$user->name = $name;
    	$user->surname = $surname;
    	$user->nick = $nick;
    	$user->email = $email;


    	// Subir la imagen 
    	# Se recoge la imagen del formulario
    	$image_path = $request->file('image_path'); //objeto

    	//Sí el objeto se recibe  se procederá en el almacenamiento
    	if($image_path){
    		//Almacenando el nombre de la imagen con la hora para que sea único
    		$image_path_name = time().$image_path->getClientOriginalName();
    		//Usando el objeto Storage y el objeto File  guardo la imagen en(storage/app/users)
    		Storage::disk('users')->put($image_path_name, File::get($image_path));

    		//Configurar el nombre de la imagen en las propiedades del objeto usuario
    		$user->image = $image_path_name;
    	}


    	//Ejecutar consulta y cambios en la base de datos.
    	$user->update();

    	//Redirección y mensaje de confirmación mediante sesión flash
    	return redirect()->route('config')
    					 ->with(['message' => 'Usuario Actualizado correctamente']);

    }

    //Método para gestión de imagenes con usuario registrado
    public function getImage($filename){
    	// Almacena la imagen en una variable 
    	$file = Storage::disk('users')->get($filename);
    	// retorna una respuesta con la variable que contiene la imagen.
    	return new Response($file, 200);
    }

    //Método para cargar el perfil de usuario
    public function profile($id){
        $user = User::find($id);

        //Retornar vista
        return view('user.profile', [
            'user' => $user
        ]);
    }

}
