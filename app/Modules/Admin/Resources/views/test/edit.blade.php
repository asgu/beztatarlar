@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать тест')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать тест - {{$test->title}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('test.index') }}">
                            Тесты
                        </a>
                        <a class="btn btn-default" href="{{ route('test.view', $test->id) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::includes.translate_buttons', ['model' => 'id', 'route' => 'test', 'id' => $test->id])
                    @include('Admin::test._form')
                </div>
            </div>
        </div>
    </div>
@stop
