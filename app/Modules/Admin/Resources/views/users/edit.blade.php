@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать пользователя')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать пользователя - {{$user->name}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('user.index') }}">
                            Пользователи
                        </a>
                        <a class="btn btn-default" href="{{ route('user.view', $user->id) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::users._form')
                </div>
            </div>
        </div>
    </div>
@stop
