@extends('Admin::layout.adminlte.page')

@section('title', 'Translation')

@section('content_header')
    <h1 class="m-0 text-dark">Translation</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <a href='{{ route("languageMessages.index") }}' class='btn btn-default'>
                            Back
                        </a>
                    </div>
                    <hr>
                    <div style="display: flex">
                        <div class="col-md-6">
                            <table class="table table-bordered" id="table">
                                <tbody>
                                <tr>
                                    <td>Id</td>
                                    <td>{{ $model->id }}</td>
                                </tr>
                                <tr>
                                    <td>Language</td>
                                    <td>{{ $model->code }}</td>
                                </tr>
                                <tr>
                                    <td>Section</td>
                                    <td>{{ $model->type }}</td>
                                </tr>
                                @if($model->file)
                                <tr>
                                    <td>File</td>
                                    <td>
                                        <a href="{{ $model->file->getFullUrl() }}" target="_blank">{{ $model->file->file_name }}</a>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Translation</td>
                                    <td><pre>{{ \App\Support\JsonSupport::encode($model->message_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

