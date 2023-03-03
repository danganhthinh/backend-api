<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui, maximum-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
{{--<meta name="description"--}}
{{--    content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">--}}
{{--<meta name="keywords"--}}
{{--    content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">--}}
<meta name="author" content="PIXINVENT">
<title>@yield('title')</title>
<link rel="apple-touch-icon" href="/backend/app-assets/images/ico/apple-icon-120.png">
<link rel="shortcut icon" type="image/x-icon" href="/backend/app-assets/images/ico/favicon.ico">
<link
    href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
    rel="stylesheet">
<!-- BEGIN VENDOR CSS-->
{{-- select2 --}}
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/forms/selects/select2.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/css/vendors.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/charts/jquery-jvectormap-2.0.3.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/charts/morris.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/extensions/unslider.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/weather-icons/climacons.min.css">
<!-- END VENDOR CSS-->
<!-- BEGIN STACK CSS-->
<link rel="stylesheet" type="text/css" href="/backend/app-assets/css/app.css">
<!-- END STACK CSS-->
<!-- BEGIN Page Level CSS-->
<link rel="stylesheet" type="text/css" href="/backend/app-assets/css/core/menu/menu-types/vertical-menu.css">
<!-- link(rel='stylesheet', type='text/css', href=app_assets_path+'/css'+rtl+'/pages/users.css')-->
<!-- END Page Level CSS-->
<!-- BEGIN Custom CSS-->

<!-- Chart css -->
<link rel="stylesheet" type="text/css" href="/backend/css/chart.css" />

<!-- Custom css-->
<link rel="stylesheet" type="text/css" href="/backend/assets/css/custom.css">
<link rel="stylesheet" type="text/css" href="/backend/assets/css/avatar.css">
<link rel="stylesheet" type="text/css" href="/backend/assets/css/question.css">
<link rel="stylesheet" type="text/css" href="/backend/assets/css/modal.css">
<link rel="stylesheet" type="text/css" href="/backend/assets/css/toast.css">
<link rel="stylesheet" type="text/css" href="/backend/css/style.css">
<link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/forms/toggle/switchery.min.css">

{{-- clockpicker --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css"
    integrity="sha512-BB0bszal4NXOgRP9MYCyVA0NNK2k1Rhr+8klY17rj4OhwTmqdPUQibKUDeHesYtXl7Ma2+tqC6c7FzYuHhw94g=="
    crossorigin="anonymous" />
{{-- datepicker --}}
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
    integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
    crossorigin="anonymous" />
{{-- daterangepicker --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- END Custom CSS-->
<!-- App css -->
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<!-- Constants JS -->
<script type="text/javascript">
    const DATE = 'DATE_ONLY';
    const WEEK = 'DATE_WEEK';
    const MONTH = 'DATE_MONTH';
    const QUARTER = 'DATE_QUARTER';
    const YEAR = 'DATE_YEAR';
    const YESTERDAY = 'YESTERDAY'; // ngày hôm qua
    const DAYS_OF_WEEK = 'DAYS_OF_WEEK'; // các ngày trong tuần
    const THIS_DAY_LAST_YEAR = 'THIS_DAY_LAST_YEAR'; // ngày này năm trước
    const ANY_ONE_DAY = 'ANY_ONE_DAY'; // 1 ngày bất kỳ
    const LAST_WEEK = 'LAST_WEEK'; // tuần trước
    const WEEKS_OF_MONTH = 'WEEKS_OF_MONTH'; // các tuần trong tháng
    const LAST_MONTH = 'LAST_MONTH'; // tháng trước
    const THIS_MONTH_LAST_YEAR = 'THIS_MONTH_LAST_YEAR'; // tháng này năm trước
    const MONTHS_OF_QUARTER = 'MONTHS_OF_QUARTER'; // các tháng trong quý
    const ANY_ONE_MONTH = 'ANY_ONE_MONTH'; // 1 tháng bất kỳ
    const LAST_QUARTER = 'LAST_QUARTER'; // quý trước
    const THIS_QUARTER_LAST_YEAR = 'THIS_QUARTER_LAST_YEAR'; // quý này năm trước
    const QUARTER_OF_YEAR = 'QUARTER_OF_YEAR'; // các quý trong năm
    const COMPARE_YEAR = 'COMPARE_YEAR'; // so sánh các năm

    const RED = 'RED';
    const BLUE = 'BLUE';
    const YELLOW = 'YELLOW';
    const ORANGE = 'ORANGE';
    const GREEN = 'GREEN';
    const PURPLE = ' PURPLE';
    const GREY = 'GREY';
    const BLACK = 'BLACK';
    const BROWN = 'BROWN';
    const COLORS = {
        RED: 'rgb(255, 99, 132)',
        BLUE: 'rgb(54, 162, 235)',
        YELLOW: 'rgb(255, 205, 86)',
        ORANGE: 'rgb(237, 149, 26)',
        GREEN: 'rgb(12, 173, 15)',
        PURPLE: 'rgb(99, 10, 138)',
        GREY: 'rgb(162, 160, 163)',
        BLACK: 'rgb(8, 7, 8)',
        BROWN: 'rgb(84, 60, 42)'
    };

    const ON = 'ON';
    const OFF = 'OFF';

    const CTV = 'CTV';
    const NV = 'NV';
</script>
