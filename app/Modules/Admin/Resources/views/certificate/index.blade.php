@extends('Admin::layout.adminlte.page')

@section('title', 'Сертификаты')

@section('content_header')
    <h1 class="m-0 text-dark">Сертификаты</h1>
@stop

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                    @include('Admin::includes.success')

                    <table class="table table-bordered" id="table">
                        <thead>
                            <tr>
                                <th class="id_column">ID</th>
                                <th>ФИО ученика</th>
                                <th>Дата получения</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td>
                                    {!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => 'ID']) !!}
                                </td>
                                <td>
                                    {!! Form::text('fio', null, ['class' => 'form-control', 'placeholder' => 'ФИО ученика']) !!}
                                </td>
                                <td>
                                    {!! Form::text('certificate.created_at', null, ['class' => 'form-control', 'id' => 'dateFilter', 'placeholder' => 'Дата получения', 'style' => 'background-color: unset']) !!}
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $('#dateFilter').flatpickr({dateFormat: 'd.m.Y'});

        $(function () {
            $('#table').DataTable({
                order: [[0, "desc"]],
                processing: true,
                serverSide: true,
                searching: true,
                ajax: '{{ route('certificate.data') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'fio', name: 'fio'},
                    {data: 'created_at', name: 'certificate.created_at'},
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

