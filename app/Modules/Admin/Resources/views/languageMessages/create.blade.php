@extends('Admin::layout.adminlte.page')

@section('title', 'Add translation')

@section('content_header')
    <h1 class="m-0 text-dark">Add translation</h1>
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

                    @include('Admin::includes.error')
                    @include('Admin::includes.success')

                    @include('Admin::languageMessages._form')
                </div>
            </div>
        </div>
    </div>
@stop
