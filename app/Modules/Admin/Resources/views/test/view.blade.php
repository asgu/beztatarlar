@extends('Admin::layout.adminlte.page')

@section('title', 'Тест')

@section('content_header')
    <h1 class="m-0 text-dark">Тест - {{ $test->title }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'test', 'params' => ['id' => $test->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('test.index') }}">
                            Тесты
                        </a>
                        <a class="btn btn-success" href="{{ route('test.edit', $test->id) }}">
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
                            <td>{{ $test->id }}</td>
                        </tr>
                        <tr>
                            <th>Урок</th>
                            <td>{{ $test->lesson ? $test->lesson->title : null }}</td>
                        </tr>
                        <tr>
                            <th>Название</th>
                            <td>{{ $test->title }}</td>
                        </tr>
                        <tr>
                            <th>Описание</th>
                            <td>{{ $test->description }}</td>
                        </tr>
                        <tr>
                            <th>Таймер</th>
                            <td>{{ $test->timer }}</td>
                        </tr>
                        <tr>
                            <th>Статус</th>
                            <td>{{ Modules\ActivityStatus\Facades\ActivityStatusFacade::statusLabel($test->status) }}</td>
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
                        <a class="btn btn-success" href="{{ route('test.question.create', ['testId' => $test->id]) }}">
                            Добавить вопрос
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <table class="table table-bordered" id="test-question-table">
                                        <thead>
                                        <tr>
                                            <th class="id_column">ID</th>
                                            <th>Вопрос</th>
                                            <th>Номер</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <td>
                                                {!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => 'ID']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('question', null, ['class' => 'form-control', 'placeholder' => 'Вопрос']) !!}
                                            </td>
                                            <td>
                                                {!! Form::text('priority', null, ['class' => 'form-control', 'placeholder' => 'Номер']) !!}
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
            $('#test-question-table').DataTable({
                order: [[2, "asc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('test.questionLink.data', ['testId' => $test->id, 'translate-lang' => $translateLang]) }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'question', name: 'question'},
                    {data: 'priority', name: 'priority'},
                    {data: 'action', name: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json"
                },
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                initComplete: function () {
                    var filters = $('#test-question-table tfoot tr');
                    $('#test-question-table thead').append(filters);

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
