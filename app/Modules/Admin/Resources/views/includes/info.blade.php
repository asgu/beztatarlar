@if(session('info'))
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-exclamation"></i> Внимание!</h5>
        {{ session()->get('info') }}
    </div>
@endif
