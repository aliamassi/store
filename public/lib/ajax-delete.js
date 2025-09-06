/*********************************************************************************************
 **
 **    Description: Delete any table record with confirmation using ajax
 **
 **********************************************************************************************/

(function () {
    let error_title = 'حدث خطأ';
    let error_msg = 'حدث خطأ ما,, الرجاء تحديث الصفحة و المحاولة مرة الاخرى';
    let validation_title = 'الرجاء التأكد من البيانات المدخلة';

    $(document).on('click', '.delete-record', function () {
        var $this = $(this);
        var url = $this.data('route');
        console.log(url);
        var loader = $('#aam-loader');
        if (typeof url === 'undefined' || !url.trim()) {
            $this.parent().parent().remove();
        } else {
            Swal.fire({
                title: 'Are You Sure ?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#eee',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.value) {
                    loader.show();
                    $.ajax({
                        type: 'DELETE',
                        url: url,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (data.status) {
                                $this.parent().parent().remove();
                                loader.hide();
                                Swal.fire({
                                    title: data.message,
                                    type: 'success',
                                    confirmButtonText: 'OK'
                                });
                            } else {
                                Swal.fire({
                                    title: data.message,
                                    type: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                            if ($this.data('redirect')) window.location.href = $this.data('redirect');
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
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: error_title,
                                text: error_msg,
                                type: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    }).always(function () {
                        loader.hide();
                    });
                } else {
                }
            });
        }

    });
})(jQuery);
