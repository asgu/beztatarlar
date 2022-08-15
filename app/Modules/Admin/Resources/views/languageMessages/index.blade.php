@extends('Admin::layout.adminlte.page')

@section('title', 'Translations')

@section('content_header')
    <h1 class="m-0 text-dark">Translations</h1>
@stop

@section('content')
    <style>
        #table_filter {
            display: none;
        }

        tfoot input, tfoot select {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
    <div class="form-group">
        <a class="btn btn-success" href="{{ route('languageMessages.create') }}">
            Add
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
                            <th>ID</th>
                            <th>Language</th>
                            <th>Section</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr class="filter-row">
                            <td>
                                <input type="text" placeholder="Id"/>
                            </td>
                            <td>
                                <input type="text" placeholder="Language"/>
                            </td>
                            <td>
                                <input type="text" placeholder="Section"/>
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
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('languageMessages.data') }}',
                    error: function (jqXHR, exception) {
                        location.reload();
                    }
                },
                columns: [
                    {data: 'id'},
                    {data: 'code'},
                    {data: 'type'},
                    {data: 'action', "orderable": false},
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/English.json"
                },
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
                    });
                }
            });
        });
    </script>
@stop

