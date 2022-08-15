@extends('Admin::layout.adminlte.page')

@section('title', 'Тесты')

@section('content_header')
    <h1 class="m-0 text-dark">Тесты</h1>
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

    <div class="form-group">
        <a class="btn btn-success" href="{{ route('test.create') }}">
            Добавить тест
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="id_column">ID</th>
                                <th>Название</th>
                                <th>Урок</th>
                                <th>Статус</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td>
                                    {!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => 'ID']) !!}
                                </td>
                                <td>
                                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Название']) !!}
                                </td>
                                <td>
                                    {!! Form::text('lesson', null, ['class' => 'form-control', 'placeholder' => 'Урок']) !!}
                                </td>
                                <td>
                                    {!! Form::select('status', Modules\ActivityStatus\Facades\ActivityStatusFacade::statuses(), null, ['class' => 'form-control', 'placeholder' => 'Выберите статус']) !!}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function () {
            $('#table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('test.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'lesson', name: 'lesson'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Russian.json"
                },
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [20, 50, 100],
                initComplete: function () {
                    var filters = $('#table tfoot tr');
                    $('#table thead').append(filters);

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

