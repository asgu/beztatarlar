@extends('Admin::layout.adminlte.page')

@section('title', 'Должность')

@section('content_header')
    <h1 class="m-0 text-dark">Должность - {{ $position->title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'position', 'params' => ['position' => $position->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('position.index') }}">
                            Должности
                        </a>
                        <a class="btn btn-success" href="{{ route('position.edit', $position->id) }}">
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
                            <td>{{ $position->id }}</td>
                        </tr>
                        <tr>
                            <th>Должность</th>
                            <td>{{ $position->title }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($position->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
