$(document).ready(function () {
    var idleState = false;
    var idleTimer = null;
    var idleTime = null;
    var activeTime = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
        var today = new Date();
        if (activeTime == null) activeTime = new Date();
        if (idleTime != null) {
            var idle_diff = (today - idleTime);
            var idle_minutes = Math.round((idle_diff / 1000) / 60);
            if (idle_minutes >= 2) {
                $.ajax({
                    url: "/employee-activity/store",
                    type: "POST",
                    data: {idle_minutes: idle_minutes},
                    success: function (response) {
                    },
                    error: function (err) {
                    }
                });
            }
        }
        clearTimeout(idleTimer);
        idleTime = null;
        if (idleState == true) {
            // $("body").css('background-color', '#fff');
        }
        // console.log('activeTime ' + activeTime);
        idleState = false;
        idleTimer = setTimeout(function () {
            if (activeTime != null) {
                var active_diff = (today - activeTime);
                var active_minutes = Math.round((active_diff / 1000) / 60);
                // console.log('active_minutes',active_minutes);
                if (active_minutes >= 1) {
                    $.ajax({
                        url: "/employee-activity/store",
                        type: "POST",
                        data: {active_minutes: active_minutes},
                        success: function (response) {
                        },
                        error: function (err) {
                        }
                    });
                }
                // console.log('active_minutes ' + active_minutes);
            }
            // $("body").css('background-color', '#000');
            idleState = true;
            idleTime = new Date();
            activeTime = null;
        }, 60 * 1000);
    });
    $("body").trigger("mousemove");


});