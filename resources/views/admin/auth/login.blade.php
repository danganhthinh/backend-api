@extends('admin.auth.layout.default')
@section('content')
@section('pageTitle', 'Login')
    <div class="login col-md-12" id="content">
        <div class="inside-block d-flex">
            <div class="form-login d-flex">
                <div class="logo-box">
                    <img src="{{ asset('backend/images/icons/logo_bridge.png') }} " alt="" width="182px"
                        height="121px" />
                </div>
                <div class="login-box">
                    <p class="title">ログイン</p>
                    <form method="post" id="formLogin" class="d-flex  box-input" action="{{ url('admin/doLogin') }}">
                        @csrf
                        {{-- <div>
                            
                            @if (Session::has('error') && !empty(Session::has('error')))
                                <div class="alert alert-danger text-center" style="margin: 10px">
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                        </div> --}}

                        <input type="text" class="account" name="account" id="account" value="{{ old('account') }}" placeholder="管理者 User ID" />
                        <p class="help-block">{!! $errors->first('account') !!}</p>
                        <input type="password" class="password" name="password" id="password" value=""
                            placeholder="パスワード" />
                        <p class="help-block">{!! $errors->first('password') !!}</p>
                        {{ Session::get('error') }}
                        <button class="login-submit" id="login">ログイン</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

