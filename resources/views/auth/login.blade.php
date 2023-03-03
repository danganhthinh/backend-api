<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link rel="stylesheet" href="{{asset('assets/css/vendor/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/vendor/bootstrap-checkbox.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/vendor/bootstrap/bootstrap-dropdown-multilevel.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/login.css')}}">
    {{--
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}"> --}}
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body class="img-backgroud"
    style="background-image: url({{asset('/assets/image/Rectangle7.png')}});">
    <div class="text-center">
        <div class="main-login">

            <div class="img-logo" style="background-image: url({{asset('/assets/image/logo.png')}});">
            </div>

            <p class="name-comp">
                CHAINOS SOLUTION
            </p>


            <form id="form-signin" class="form-signin" action=" {{url('doLogin')}} " method="post">
                {{ csrf_field() }}
                <section>
                    <div>
                        @if(Session::has('success'))
				            <div class="alert alert-success text-center" style="margin: 10px">
                            {{Session::get('success')}}
                        </div>
                        @endif
                        @if(Session::has('error') && !empty(Session::has('error')))

				            <div class="alert alert-danger text-center" style="margin: 10px">
                            {{Session::get('error')}}
                        </div>
                        @endif
                    </div>
                    <div>
                        <input type="text" class="name" name="name" placeholder="Username" autofocus>
                        {{-- <div class="input-group-addon"><i class="fa fa-user"></i></div> --}}
                    </div>
                    <div>
                        <input type="password" class="password" name="password" placeholder="Password">
                        {{-- <div class="input-group-addon"><i class="fa fa-key"></i></div> --}}
                    </div>
                </section>

                <section class="controls">
                    <a href="/reset-password" style="font-family: 'Roboto' !important;">
                        Forgot password?
                    </a>
                </section>
                {{-- <section class="log-in"> --}}
                    <button class="log-in" type="submit">Login</button>

                    {{--
                </section> --}}
            </form>
        </div>
    </div>
</body>
</html>
