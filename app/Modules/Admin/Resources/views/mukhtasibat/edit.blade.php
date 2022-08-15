@extends('Admin::layout.adminlte.page')

@section('title', 'Редактировать мухтасибат')

@section('content_header')
    <h1 class="m-0 text-dark">Редактировать мухтасибат - {{$mukhtasibat->title}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('mukhtasibat.index') }}">
                            Мухтасибаты
                        </a>
                        <a class="btn btn-default" href="{{ route('mukhtasibat.view', $mukhtasibat->id) }}">
                            Просмотр
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')
                    @include('Admin::includes.translate_buttons', ['model' => 'mukhtasibat', 'route' => 'mukhtasibat', 'id' => $mukhtasibat->id])

                    @include('Admin::mukhtasibat._form')
                </div>
            </div>
        </div>
    </div>
@stop
