<div class="btn-group" role="group">
    @if (isset($isView) && $isView)
        @if (isset($viewRoute))
            <a href='{{ $viewRoute  }}' class='btn btn-success btn-sm'>
                <i class="fas fa-eye"></i>
            </a>
        @else
            <a href='{{ route("$route.view", $id) }}' class='btn btn-success btn-sm'>
                <i class="fas fa-eye"></i>
            </a>
        @endif
    @endif
    @if (isset($isEdit) && $isEdit)
        @if (isset($editRoute))
            <a href='{{ $editRoute }}' class='btn btn-info btn-sm'>
                <i class="fas fa-edit"></i>
            </a>
        @else
            <a href='{{ route("$route.edit", $id) }}' class='btn btn-info btn-sm'>
                <i class="fas fa-edit"></i>
            </a>
        @endif
    @endif
    @if (!isset($isDeleted) || !$isDeleted)
        @if (isset($deleteRoute))
            <button type="submit" class='btn btn-danger btn-sm delete_js-button' data-rout="{{ $deleteRoute }}" data-id="{{ $id }}">
                <i class="fas fa-trash"></i>
            </button>
        @else
            <button type="submit" class='btn btn-danger btn-sm delete_js-button' data-rout="{{ $route }}"
                    data-id="{{ $id }}">
                <i class="fas fa-trash"></i>
            </button>
        @endIf
    @endif
</div>
