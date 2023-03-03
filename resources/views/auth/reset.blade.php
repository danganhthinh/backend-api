@extends('auth.layout.default')
@section('pageTitle', 'Reset Password')
@section('content')
    <div id="content-reset-password">
        <div class="inside-block">
            <div class="title reset-password" style="height: 300px !important;overflow: hidden !important;">
                <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible ol-md-11 col-sm-11" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    <div class="clearfix"></div>
                    <h2 class="normal" style="text-align: center">Change Password</h2>
                </div>
                <div class="tiles grey p-t-20 p-b-20 no-margin text-black tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tab_login">
                        <?= Form::open(['url' => 'reset', 'method' => 'POST']) ?>
                        <input type="text" name="id" value="<?php echo $id; ?>" class="hidden">
                        <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10 div-center" style="margin-top: 20px">
                            <div class="col-md-8 col-sm-8" style="margin-top: 15px">
                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    value="{{ old('password') }}">
                            </div>
                            <div class="col-md-8 col-sm-8" style="margin-top: 15px">
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                            </div>
                            <div class="col-md-8 col-sm-8" style="margin-top: 20px">
                                <button type="submit" class="btn btn-primary btn-block">Submit</button>
                            </div>
                        </div>
                        <?= Form::close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
