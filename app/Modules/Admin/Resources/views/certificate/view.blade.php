@extends('Admin::layout.adminlte.page')

@section('title', 'Сертификат')

@section('content_header')
    <h1 class="m-0 text-dark">Сертификат ученика {{ \Modules\User\Modules\Profile\Facades\UserProfileFacade::fullName($profile) }}</h1>
@stop

@section('content')
    <style>
        #table_filter {
            display: none;
        }

        td.no-line-break {
            white-space: nowrap;
        }

        .dataTables_wrapper .row:nth-child(2) {
            overflow-x: auto;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('certificate.index') }}">
                            Сертификаты
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="certificate-table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $profile->id }}</td>
                        </tr>
                        <tr>
                            <th>ФИО ученика</th>
                            <td>{{ \Modules\User\Modules\Profile\Facades\UserProfileFacade::fullName($profile) }}</td>
                        </tr>
                        <tr>
                            <th>Дата получения</th>
                            <td>{{ \App\Helpers\DateHelper::formatDate($profile->certificate->created_at) }}</td>
                        </tr>
                        <tr>
                            <th>Сертификат</th>
                            <td>
                                <a target="_blank" href="{{ $profile->certificate->full_url }}">
                                    {{ $profile->certificate->file_name }}
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
