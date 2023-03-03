<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mỹ Phẩm - Nha Khoa') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i"
      rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/forms/icheck/custom.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/css/app.css">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/css/pages/login-register.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="/backend/assets/css/style.css">
    <!-- END Custom CSS-->

</head>
<body class="vertical-layout vertical-menu 1-column   menu-expanded blank-page blank-page"
data-open="click" data-menu="vertical-menu" data-col="1-column" style="overflow-y:hidden;height:92vh">
    <div class="app-content content">
        <div class="content-wrapper" style="overflow-x: auto; height: 100vh;">
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        @yield('content')
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="/backend/app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="/backend/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="/backend/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js"
      type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN STACK JS-->
    <script src="/backend/app-assets/js/core/app-menu.js" type="text/javascript"></script>
    <script src="/backend/app-assets/js/core/app.js" type="text/javascript"></script>
    <script src="/backend/app-assets/js/scripts/customizer.js" type="text/javascript"></script>
    <!-- END STACK JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="/backend/app-assets/js/scripts/forms/form-login-register.js" type="text/javascript"></script>
    <script src="/backend/js/custom.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
    <script>var OneSignal = OneSignal || [];</script>
</body>
</html>
