<!-- Comprobando que la imagen existe para el usuario -->
@if(Auth::user()->image)
	{{-- <img src="{{ url('/user/avatar/'.Auth::user()->image) }}"> --}}
	<div class="container-avatar">		
		<img src="{{ route('user.avatar', ['filename' => Auth::user()->image]) }}" class="avatar">
	</div>
@endif