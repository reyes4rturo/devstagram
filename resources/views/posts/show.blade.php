@extends('layouts.app')

@section('titulo')
    {{ $post->titulo }}
@endsection

@section('contenido')
    <div class="container mx-auto md:flex">
        <div class="md:w-1/2">
            <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen de {{ $post->titulo }}" srcset="">
            <div class="p-3 flex items-center gap-2">
                @auth
                    <livewire:like-post :post="$post" />
                @endauth


            </div>
            <div class="font-bold">
                <p>{{ $post->user->username }}</p>
                <p class="text-sm text-gray-500"> {{ $post->created_at->diffForHumans() }}</p>
                <p class="mt-5">{{ $post->descripcion }}</p>
            </div>
            @auth
                @if ($post->user_id === auth()->user()->id)
                    <form action="{{ route('posts.destroy', $post) }}" method="post">
                        @method('DELETE')
                        @csrf
                        <input type="submit"
                            class=" mt-2 p-2 font-bold text-sm bg-red-500 rounded-lg text-white hover:bg-red-700"value="Eliminar publicacion">
                    </form>
                @endif
            @endauth

        </div>
        <div class="md:w-1/2 p-5">
            <div class="shadow bg-white p-5 mb-5">
                @auth
                    <p class="text-xl font-bold text-center mb-4">Agrega un comentario</p>
                    @if (session('mensaje'))
                        <div class="bg-green-500 p-2 rounded-lg mb-6 text-white uppercase font-bold">
                            {{ session('mensaje') }}
                        </div>
                    @endif
                    <form action="{{ route('comentarios.store', ['user' => $user, 'post' => $post]) }}" method="post">
                        @csrf
                        <div class="mb-5">
                            <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold">Agrega
                                Comentario</label>

                            <textarea id="comentario" name="comentario" placeholder="Tu Comentario"
                                class="border p-3 w-full rounded-lg @error('comentario') border-red-500 @enderror"></textarea>
                            @error('comentario')
                                <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="submit"
                            class=" bg-sky-600 p-4 w-full hover:bg-sky-800 font-bold text-white rounded-lg cursor-pointer transition-colors"
                            value="Comentar">
                    </form>
                @endauth
                <div class="bg-white shadow mb-5 max-h-96 overflow-y-scroll mt-10">
                    @if ($post->comentarios->count())
                        @foreach ($post->comentarios as $comentario)
                            <div class="p-5 border-gray-300 border-b">
                                <a href="{{ route('posts.index', $comentario->user) }}"
                                    class="font-bold">{{ $comentario->user->username }}</a>
                                <p>{{ $comentario->comentario }}</p>
                                <p class="text-gray-500 text-sm">{{ $comentario->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="p-10 text-center">No hay comentarios aun</p>
                    @endif
                </div>
            </div>
        </div>
    @endsection
