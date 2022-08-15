@extends('Admin::layout.adminlte.page')

@section('title', 'Приход')

@section('content_header')
    <h1 class="m-0 text-dark">Приход - {{ $parish->title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'parish', 'params' => ['parish' => $parish->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('parish.index') }}">
                            Приходы
                        </a>
                        <a class="btn btn-success" href="{{ route('parish.edit', $parish->id) }}">
                            Редактировать
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $parish->id }}</td>
                        </tr>
                        <tr>
                            <th>Наименование</th>
                            <td>{{ $parish->title }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($parish->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
