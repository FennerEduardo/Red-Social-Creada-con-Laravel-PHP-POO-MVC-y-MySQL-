<html lang="es">
    <head>
        <title>Usuarios</title>
        <meta charset="utf-8">
        <style>
            .table, .table tbody tr, .table thead tr{
                border: solid 2px #888;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 >Descargar PDF de usuarios</h1>
                </div>
                <div class="col-md-8 table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Nick</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}} {{$user->surname}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->nick}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
