@extends('Admin::layout.adminlte.page')

@section('title', 'Добавить тест')

@section('content_header')
    <h1 class="m-0 text-dark">Добавить тест</h1>
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
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::test._form')
                </div>
            </div>
        </div>
    </div>
@stop
