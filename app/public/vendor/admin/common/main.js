$(document).ready(function() {
    $('.summernote').summernote();
    $('.select2').select2();

    $(document).on('click', '.delete_js-button', function () {
        const isDelete = confirm('Вы действительно хотите удалить запись?')
        if (!isDelete) {
            return false;
        } else {
            $.ajax({
                type: 'DELETE',
                url: '/admin/' + $(this).data('rout') + '/' + $(this).data('id'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                success: function(data){
                    location.reload();
                },
                error: function (jqXHR, exception) {
                    alert(jqXHR.responseJSON.message);
                }
            });
        }
    })
})
