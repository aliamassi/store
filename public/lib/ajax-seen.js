/*********************************************************************************************
 **
 **
 **********************************************************************************************/

(function () {
    let error_title = 'حدث خطأ';
    let error_msg = 'حدث خطأ ما,, الرجاء تحديث الصفحة و المحاولة مرة الاخرى';
    let validation_title = 'الرجاء التأكد من البيانات المدخلة';

    $(document).on('click', '.seen-record', function () {
        var $this = $(this);
        var url = $this.data('route');
        var seen = $this.data('seen');
        var loader = $('#aam-loader');
        Swal.fire({
            title: 'هل انت متأكد ؟',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#eee',
            confirmButtonText: 'نعم',
            cancelButtonText: 'إلغاء',
        }).then((result) => {
            if (result) {
                loader.show();
                $.ajax({
                    type: 'PATCH',
                    url: url,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.status) {
                            var icon = $this.find('i');
                            icon.removeClass('fa fa-eye');
                            icon.addClass('fa fa-eye-slash');
                            loader.hide();
                            Swal.fire({
                                title: data.message,
                                type: 'success',
                                confirmButtonText: 'موافق'
                            });
                        } else {
                            Swal.fire({
                                title: data.message,
                                type: 'error',
                                confirmButtonText: 'موافق'
                            });
                        }
                    }

                }).fail(function (jqXhr) {
                    loader.hide();
                    if (jqXhr.status === 422) {
                        var resp = jqXhr.responseJSON;
                        var errorsHtml = '<ul>';
                        $.each(resp.errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                        errorsHtml += '</ul>';
                        Swal.fire({
                            title: resp.message,
                            html: errorsHtml,
                            type: 'error',
                            confirmButtonText: 'موافق'
                        });
                    } else {
                        Swal.fire({
                            title: error_title,
                            text: error_msg,
                            type: 'error',
                            confirmButtonText: 'موافق'
                        });
                    }
                }).always(function () {
                    loader.hide();
                });
            } else {
            }
        });
    });
})(jQuery);
