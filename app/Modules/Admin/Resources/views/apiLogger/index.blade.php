@extends('Admin::layout.adminlte.page')

@section('title', 'System Log')

@section('content_header')
    <h1 class="m-0 text-dark">System Log</h1>
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

        .id_column {
            max-width: 15px !important;
        }

        .id_column input {
            max-width: 30px !important;
        }

        .small_column {
            max-width: 45px !important;
        }

        .small_column input {
            max-width: 60px !important;
        }

        .url_column {
            max-width: 180px !important;
        }

        .url_column input {
            max-width: 200px !important;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered" id="table">
                        <thead>
                        <tr>
                            <th class="id_column">ID</th>
                            <th>Datetime</th>
                            <th class="url_column">Url</th>
                            <th class="small_column">Method</th>
                            <th class="small_column">User Id</th>
                            <th class="small_column">Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr class="filter-row">
                            <td class="id_column">
                                <input type="text" placeholder="Id"/>
                            </td>
                            <td>
                                <input type="text" placeholder="Datetime"/>
                            </td>
                            <td class="url_column">
                                <input type="text" placeholder="Url"/>
                            </td>
                            <td class="small_column">
                                <input type="text" placeholder="Method"/>
                            </td>
                            <td class="small_column">
                                <input type="text" placeholder="User Id"/>
                            </td>
                            <td></td>
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
                ajax: '{{ route('apiLogger.data') }}',
                columns: [
                    {data: 'id', name: 'id', className: 'id_column'},
                    {data: 'date', name: 'date'},
                    {data: 'url', name: 'url', className: 'url_column'},
                    {data: 'method', name: 'method', className: 'small_column'},
                    {data: 'user_id', name: 'user_id', className: 'small_column'},
                    {data: 'status_code', name: 'status_code', className: 'small_column'},
                    {data: 'action', name: 'action'}
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
