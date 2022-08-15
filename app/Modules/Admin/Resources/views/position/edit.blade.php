@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать должность')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать должность - {{$position->title}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('position.index') }}">
                            Должности
                        </a>
                        <a class="btn btn-default" href="{{ route('position.view', $position->id) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')
                    @include('Admin::includes.translate_buttons', ['model' => 'position', 'route' => 'position', 'id' => $position->id])

                    @include('Admin::position._form')
                </div>
            </div>
        </div>
    </div>
@stop
