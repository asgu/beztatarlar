@extends('Admin::layout.adminlte.page')

@section('title', 'Мухтасибат')

@section('content_header')
    <h1 class="m-0 text-dark">Мухтасибат - {{ $mukhtasibat->title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'mukhtasibat', 'params' => ['mukhtasibat' => $mukhtasibat->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('mukhtasibat.index') }}">
                            Мухтасибаты
                        </a>
                        <a class="btn btn-success" href="{{ route('mukhtasibat.edit', $mukhtasibat->id) }}">
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
                            <td>{{ $mukhtasibat->id }}</td>
                        </tr>
                        <tr>
                            <th>Мухтасибат</th>
                            <td>{{ $mukhtasibat->title }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($mukhtasibat->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
