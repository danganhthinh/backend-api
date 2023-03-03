$(document).ready(function () {
    $(document).ajaxSend(function () {
        $('.loader').fadeIn(250);
    });
    $(document).ajaxComplete(function () {
        $('.loader').fadeOut(250);
    });
});