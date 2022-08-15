@extends('Admin::layout.adminlte.page')

@section('title', 'Добавить урок')

@section('content_header')
    <h1 class="m-0 text-dark">Добавить урок</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('lesson.index') }}">
                            Уроки
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::lesson._form')
                </div>
            </div>
        </div>
    </div>
@stop
