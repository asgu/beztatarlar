@extends('Admin::layout.adminlte.page')

@section('title', 'Вопрос')

@section('content_header')
    <h1 class="m-0 text-dark">Вопрос - {{ $question->question }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.translate_buttons_params_view', ['route' => 'test.question', 'params' => ['testId' => $testId, 'id' => $question->id]])
                    <div class="btn-group" role="group">
                        <a class="btn btn-default" href="{{ route('test.view', $testId) }}">
                            Вопросы
                        </a>
                        <a class="btn btn-success" href="{{ route('test.question.edit', ['testId' => $testId, 'id' => $question->id]) }}">
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
                            <td>{{ $question->id }}</td>
                        </tr>
                        <tr>
                            <th>Вопрос</th>
                            <td>{{ $question->question }}</td>
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
                        <a class="btn btn-success" href="{{ route('test.answer.create', ['questionId' => $question->id]) }}">
                            Добавить ответ
                        </a>
                    </div>

                    <table class="table table-bordered" id="answers-table">
                        <thead>
                        <tr>
                            <th class="id_column">ID</th>
                            <th>Ответ</th>
                            <th>Верный</th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#answers-table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('test.answer.data', ['questionId' => $question->id, 'translate-lang' => $translateLang]) }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'answer', name: 'answer'},
                    {data: 'is_correct', name: 'is_correct'},
                    {data: 'action', name: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json"
                },
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                initComplete: function () {
                    var filters = $('#answers-table tfoot tr');
                    $('#answers-table thead').append(filters);

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
