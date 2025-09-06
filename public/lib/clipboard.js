"use strict";
// Class definition

var KTClipboardDemo = function () {

    // Private functions
    var demos = function () {
        // basic example
        new ClipboardJS('[data-clipboard=true]').on('success', function (e) {
            var $this = $(this);
            var id = e.trigger.dataset.clipboardTarget;
            var tooltip = $('*[data-clipboard-target="' + id + '"]');
            tooltip.attr('data-original-title', 'Copied!').tooltip('show');
            tooltip.attr('data-original-title', 'Copy to clipboard');
            e.clearSelection();
            // alert('Copied!');
        });
    }

    return {
        // public functions
        init: function () {
            demos();
        }
    };
}();

jQuery(document).ready(function () {
    KTClipboardDemo.init();
});
