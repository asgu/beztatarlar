@extends('Admin::layout.adminlte.page')
@inject('jsonFormatter', 'Modules\Admin\Formatters\JsonFormatter')
@section('title', 'Log')

@section('content_header')
    <h1 class="m-0 text-dark">Log №{{ $log->id }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        {!! link_to_route('apiLogger.index', 'Back', false, ['class' => 'btn btn-default']) !!}
                    </div>
                    <table class="table table-head-fixed table-hover table-striped">
                        <thead>
                        <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{ $log->id }}</td>
                            </tr>
                            <tr>
                                <td>Datetime</td>
                                <td>{{ $log->date }}</td>
                            </tr>
                            <tr>
                                <td>Duration (in microseconds)</td>
                                <td>{{ $log->duration }}</td>
                            </tr>
                            <tr>
                                <td>URL</td>
                                <td>{{ $log->url }}</td>
                            </tr>
                            <tr>
                                <td>Method</td>
                                <td>{{ $log->method }}</td>
                            </tr>
                            <tr>
                                <td>Request</td>
                                <td>{{ $jsonFormatter::unescapedUnicode($log->request) }}</td>
                            </tr>
                            <tr>
                                <td>Answer</td>
                                <td>{{ $jsonFormatter::unescapedUnicode($log->answer) }}</td>
                            </tr>
                            <tr>
                                <td>Headers</td>
                                <td>{{ $log->headers }}</td>
                            </tr>
                            <tr>
                                <td>Status code</td>
                                <td>{{ $log->status_code }}</td>
                            </tr>
                            <tr>
                                <td>Client IP</td>
                                <td>{{ $log->client_ip }}</td>
                            </tr>
                            <tr>
                                <td>User ID</td>
                                <td>{{ $log->user_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
