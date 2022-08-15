@extends('Admin::layout.adminlte.page')

@section('title', 'Урок')

@section('content_header')
    <h1 class="m-0 text-dark">Урок - {{ $lesson->title }}</h1>
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
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'lesson', 'params' => ['lesson' => $lesson->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('lesson.index') }}">
                            Уроки
                        </a>
                        <a class="btn btn-success" href="{{ route('lesson.edit', $lesson->id) }}">
                            Редактировать
                        </a>
                    </div>
                    <hr>

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="lesson-table">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{ $lesson->id }}</td>
                        </tr>
                        <tr>
                            <th>Название</th>
                            <td>{{ $lesson->title }}</td>
                        </tr>
                        <tr>
                            <th>Порядковый номер</th>
                            <td>{{ $lesson->priority }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($lesson->status) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <a class="btn btn-success" href="{{ route('lessonTopic.create', $lesson->id) }}">
                            Добавить топик
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table class="table table-bordered" id="topic-table">
                                        <thead>
                                        <tr>
                                            <th class="id_column">ID</th>
                                            <th>Название</th>
                                            <th>Порядок</th>
                                            <th>Статус</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td>
                                                {!! Form::text('topic.id', null, ['class' => 'form-control', 'placeholder' => 'ID']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('topic.title', null, ['class' => 'form-control', 'placeholder' => 'Название']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('topic.priority', null, ['class' => 'form-control', 'placeholder' => 'Порядок']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('topic.status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), null, ['class' => 'form-control', 'placeholder' => 'Выберите статус']) !!}
                                            </td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#topic-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('lessonTopic.data', ['lessonId' => $lesson->id, 'translate-lang' => $translateLang]) }}',
                columns: [
                    {data: 'topic.id', name: 'id'},
                    {data: 'topic.title', name: 'title'},
                    {data: 'topic.priority', name: 'priority'},
                    {data: 'topic.status', name: 'status'},
                    {data: 'action', name: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json"
                },
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                columnDefs: [{
                    "defaultContent": 0,
                    "targets": 2
                }],
                initComplete: function () {
                    var filters = $('#topic-table tfoot tr');
                    $('#topic-table thead').append(filters);

                    this.api().columns().every(function () {
                        var column = this;

                        $('input', this.footer()).on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });

                        $('select', this.footer()).on('change', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    });
                }
            });
        });
    </script>
@stop
