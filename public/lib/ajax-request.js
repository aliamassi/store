/*********************************************************************************************
 **
 **    Description: Convert any html form to ajax post form
 **
 **********************************************************************************************/


(function () {
    if (!$('.ajax-request').length) return;
    $('.ajax-request').each(function (index) {
        var loader = $('#aam-loader');
        $(this).submit(function (e) {
            e.preventDefault();
            if (typeof (CKEDITOR) !== "undefined") for (instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement();
            var form = $(this);
            var _this = this;
            var formData = new FormData(this);
            var subbtn = form.find(":submit");
            subbtn.prop('disabled', true);
            loader.show();

            $.ajax({
                type: 'POST',
                url: form.prop('action'),
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                    loader.hide();
                    $('.modal-backdrop').remove();
                    if (data.status) {
                        if(form.data('remove')){
                            form.parents('.cart-item').remove();
                            console.log(data);
                             $('#mobile-cart-bar').html(data.mobile_cart_view);
                        }
                        if(form.data('decrease')){
                            form.siblings('.qty').html("Qty: "+data.qty);
                            if(data.qty === 1){
                                form.find('.decrease-btn').remove();
                            }
                            if(data.qty <= 0){
                                form.parents('.cart-item').remove();
                            }
                        }
                    }
                }
            }).fail(function (jqXhr) {
                subbtn.prop('disabled', false);
                loader.hide();
                var resp = jqXhr.responseJSON;
                if (jqXhr.status === 422) {
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
                } else if (jqXhr.status === 419) {
                    Swal.fire({
                        title: resp.title,
                        text: resp.message,
                        type: 'error',
                        confirmButtonText: 'OK'
                    });
                } else if (resp.message) {
                    Swal.fire({
                        title: resp.title,
                        text: resp.message,
                        type: 'error',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: window.error_title,
                        text: window.error_msg,
                        type: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            }).always(function () {
                subbtn.prop('disabled', false);
                loader.hide();
            });

        });
    });
})(jQuery);
