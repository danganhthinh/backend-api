@extends('layouts.master')
@section('content')
@section('pageTitle', 'Reset Password')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="#" class="text-dark">パスワードの変更</a></h5>
                    </h5>
                    <h2 class="card-title">パスワードの変更</h2>
                </div>
                <div class="card-content mt-4">
                    <div class="card-body">
                        <form action="{{ url('/admin/user/change-password') }}" method="post"
                            id="form-change-password">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-regist">
                                <fieldset class="form-group">
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">元のパスワード</label>
                                        <div class="d-block">
                                            <input type="password" name="current_password" class="password"
                                                placeholder="" id="current_password" value=""
                                                data-toggle="password"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                                maxlength="12">
                                            <span toggle="#password-field"
                                                class="fa fa-fw fa-eye-slash field_icon toggle-password-current-password"></span>

                                            <p class="text-danger error-text current_password_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">新規パスワード</label>
                                        <div class="d-block">
                                            <input type="password" name="password" class="password" placeholder=""
                                                id="password" value=""
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                                maxlength="12">
                                            <span toggle="#password-field"
                                                class="fa fa-fw fa-eye-slash field_icon toggle-password"></span>
                                            <p class="text-danger error-text password_error"></p>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="d-block position-relative">
                                            <label for=""
                                                style="height: 80px; vertical-align: top">新規パスワード&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; （確認用）</label>
                                            <div class="d-inline-block">
                                                <input type="password" name="confirm_password" class="password"
                                                    placeholder="" id="confirm_password" value=""
                                                    style="height: 40px; margin-left: 16px"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                                    maxlength="12">
                                                <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye-slash field_icon toggle-password-confirm-password"
                                                    style=""></span>

                                                <p class="text-danger error-text confirm_password_error"
                                                    style=""></p>
                                            </div>
                                            <button type="button" class="float-right btn btn-change-password"
                                                id="change-password">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録
                                            </button>
                                        </div>


                                    </div>

                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
<script>
    $(document).on('click', '.toggle-password-current-password', function() {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $('#current_password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass("fa-eye-slash").css('color', "#c1c1c1");
            $(this).addClass("fa-eye").css('color', "#000000");
        } else if (input.attr('type') === 'text') {
            input.attr('type', 'password');
            $(this).removeClass("fa-eye").css('color', "#000000");
            $(this).addClass("fa-eye-slash").css('color', "#c1c1c1");
        }
    });
    $(document).on('click', '.toggle-password', function() {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $('#password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass("fa-eye-slash").css('color', "#c1c1c1");
            $(this).addClass("fa-eye").css('color', "#000000");
        } else if (input.attr('type') === 'text') {
            input.attr('type', 'password');
            $(this).removeClass("fa-eye").css('color', "#000000");
            $(this).addClass("fa-eye-slash").css('color', "#c1c1c1");
        }
    });
    $(document).on('click', '.toggle-password-confirm-password', function() {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $('#confirm_password');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass("fa-eye-slash").css('color', "#c1c1c1");
            $(this).addClass("fa-eye").css('color', "#000000");
        } else if (input.attr('type') === 'text') {
            input.attr('type', 'password');
            $(this).removeClass("fa-eye").css('color', "#000000");
            $(this).addClass("fa-eye-slash").css('color', "#c1c1c1");
        }
    });
</script>
<script src="/backend/assets/js/change-password-auth.js"></script>
@endsection
