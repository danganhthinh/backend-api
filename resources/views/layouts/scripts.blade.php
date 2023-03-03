<!-- BEGIN VENDOR JS-->
<script src="/backend/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="/backend/app-assets/vendors/js/extensions/jquery.knob.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/js/scripts/extensions/knob.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/charts/raphael-min.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/charts/morris.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/charts/jvector/jquery-jvectormap-2.0.3.min.js" type="text/javascript">
</script>
<script src="/backend/app-assets/vendors/js/charts/jvector/jquery-jvectormap-world-mill.js" type="text/javascript">
</script>
<script src="/backend/app-assets/data/jvector/visitor-data.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/charts/chart.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/charts/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/sort/jquery-ui.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/extensions/unslider-min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="/backend/app-assets/css/core/colors/palette-climacon.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/fonts/simple-line-icons/style.min.css">
<!-- END PAGE VENDOR JS-->
{{-- <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/forms/selects/select2.min.css"> --}}
<script src="/backend/app-assets/vendors/js/forms/select/select2.full.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/backend/app-assets/js/scripts/forms/select/form-select2.js" type="text/javascript"></script>
<script src="/backend/app-assets/vendors/js/forms/toggle/bootstrap-checkbox.min.js" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN STACK JS-->
<script src="/backend/app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="/backend/app-assets/js/core/app.js" type="text/javascript"></script>
<script src="/backend/app-assets/js/scripts/customizer.js" type="text/javascript"></script>
<!-- END STACK JS-->
<!-- BEGIN PAGE LEVEL JS-->
{{-- <script src="/backend/app-assets/js/scripts/pages/dashboard-analytics.js" type="text/javascript"></script> --}}
<!-- END PAGE LEVEL JS-->

<!-- CKEDITOR -->
{{-- <script src="/ckeditor/ckeditor.js" type="text/javascript"></script> --}}
<!-- END CKEDITOR -->
<script src="/backend/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>

{{-- clock n date picker --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.min.js"
    integrity="sha512-x0qixPCOQbS3xAQw8BL9qjhAh185N7JSw39hzE/ff71BXg7P1fkynTqcLYMlNmwRDtgdoYgURIvos+NJ6g0rNg=="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
    integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
    crossorigin="anonymous"></script>
<script src="/backend/app-assets/vendors/js/forms/toggle/switchery.min.js" type="text/javascript"></script>
{{-- daterangepicker --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/df-number-format/2.1.6/jquery.number.min.js"></script>
<script src="/backend/js/chart.js" type="text/javascript"></script>
<script src="/backend/js/common.js" type="text/javascript"></script>
<script src="/backend/js/custom.js" type="text/javascript"></script>
<script src="/backend/app-assets/js/scripts/forms/switch.js" type="text/javascript"></script>

{{-- debounce --}}
<script src="/backend/assets/js/debounce.js"></script>
<script type="text/javascript">
    var main_image = "{{ __('common.add_main_image') }}";
    var make_main_image = "{{ __('common.add_make_main_image') }}";
</script>
<script type="text/javascript" src="/backend/js/moment.js"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
