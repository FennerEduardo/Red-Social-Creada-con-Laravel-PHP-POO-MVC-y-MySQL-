<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Usar modelo de imagenes para la prueba de ORM
# use App\Image;


Route::get('/', function () {

/* ********** Prueba de ORM ***************
	// Obtener todas las imagenes de la base de datos, en formato de objeto
	$images = Image::all();
	foreach ($images as $image) {
		echo $image->image_path.'<br>';
		echo $image->description.'<br>';
		echo $image->user->name.' '.$image->user->surname.'<br>';

		if(count($image->comments) >= 1){ 
			echo '<h4>Comentarios</h4>';
			foreach ($image->comments as $comment) {
				echo $comment->user->name.' '.$comment->user->surname.': ';
				echo $comment->content.'<br>';
			}
		}
		echo 'LIKES: '.count($image->likes);
		echo '<hr>';
	}

	die();
********* Fin de prueba ORM **************/
    return view('welcome');
});

// ****** RUTAS GENERALES ***************
Auth::routes();
//Ruta de bienvenida al login
Route::get('/', 'HomeController@index')->name('home');


/**********RUTAS PARA USUARIOS ************** */

// Ruta la configuración de los usuarios
Route::get('/configuracion', 'UserController@config')->name('config');
// Ruta para la actualización  de los usuarios
Route::post('/user/update', 'UserController@update')->name('user.update');
// Ruta para mostrar la imagen
Route::get('/user/avatar/{filename}', 'UserController@getImage')->name('user.avatar');
// Ruta para eliminar  los like
Route::get('/perfil/{id}', 'UserController@profile')->name('profile');
// Ruta para mostrar todos los usuarios de la red
Route::get('/gente/{search?}', 'UserController@index')->name('user.index');

/**********RUTAS PARA IMAGENES ****************** */
// Ruta para la creación de imagénes
Route::get('/subir-imagen', 'ImageController@create')->name('image.create');
// Ruta para guardar las imagenes que suben los usuarios
Route::post('/image/save', 'ImageController@save')->name('image.save');
// Ruta para mostrar la imagen
Route::get('/image/file/{filename}', 'ImageController@getImage')->name('image.file');
// Ruta para mostrar el detalle de  la imagen
Route::get('/image/{id}', 'ImageController@detail')->name('image.detail');
// Ruta para eliminar una imagen
Route::get('/image/delete/{id}', 'ImageController@delete')->name('image.delete');
// Ruta para editar las imagenes
Route::get('/image/editar/{id}', 'ImageController@edit')->name('image.edit');
// Ruta para guardar las imagenes que editan los usuarios
Route::post('/image/update', 'ImageController@update')->name('image.update');

/**********RUTAS PARA COMENTARIOS ****************** */
// Ruta para guardar los comentarios
Route::post('/comment/save', 'CommentController@save')->name('comment.save');
// Ruta para eliminar un comentario
Route::get('/comment/delete/{id}', 'CommentController@delete')->name('comment.delete');

/**********RUTAS PARA LIKES ****************** */
// Ruta para guardar los like
Route::get('/like/{image_id}', 'LikeController@like')->name('like.save');
// Ruta para eliminar  los like
Route::get('/dislike/{image_id}', 'LikeController@dislike')->name('like.delete');
//Ruta de likes
Route::get('/likes', 'LikeController@index')->name('likes');


