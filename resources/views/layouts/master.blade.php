<!DOCTYPE html>
<html>

<head>
    <title>@yield('pageTitle') - Bridge LMS</title>
    @include('layouts.head')
    @yield('head')

</head>

<body class="vertical-layout vertical-menu 2-columns fixed-navbar menu-expanded pace-done" data-open="click"
    data-menu="vertical-menu" data-col="2-columns" style="overflow-y:hidden">
    <!-- Header -->
    <header>
        @include('layouts.header')
    </header>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="app-content content" style="overflow: auto;height:100%">
        <div class="content-wrapper">
            @if (session('status'))
                <div class="alert-message">{{ session('status') }}</div>
            @endif
            <!-- Main content -->
            <div class="loader"></div>
            @yield('content')
            <!-- /.content -->
        </div>
    </div>



    @include('layouts.scripts')
    @yield('scripts')
</body>

</html>
