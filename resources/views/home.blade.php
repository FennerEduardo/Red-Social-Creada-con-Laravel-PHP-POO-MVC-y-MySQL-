@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{route('user.pdf')}}" >Descargar PDF de usuarios</a> ||
            <a href="{{route('user.excel')}}" >Descargar Excel de usuarios</a>
        </div>
        <div class="col-md-8">
            @include('includes.message')
            
            @foreach($eventos as $evento)
            <!--Evento: {{$evento->name}}<br>-->
            <?php var_dump($evento); ?>
            @endforeach
            @foreach($images as $image)
                @include('includes.image', ['image' => $image])
            @endforeach
            <!-- Paginación -->
            <div class="clearfix"></div>
                {{$images->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
