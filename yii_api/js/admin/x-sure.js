(function ($) {
    "use strict";
    $.fn.linkConfirm = function (msg) {
        $(this).on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var dlg = confirm(msg, function () {
                layer.close(dlg);
                window.location.href = url;
            })
        });
    };


    $('.item-delete').linkConfirm('确定要删除该项？');
})(jQuery);