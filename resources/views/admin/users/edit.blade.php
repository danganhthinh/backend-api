@extends('layouts.master')
@section('content')
@section('pageTitle', 'Users')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="{{ url('admin/user') }}">ユーザー一覧</a>　〉<a
                            href="#" class="text-dark">ユーザー情報の編集</a></h5>
                    <h2 class="card-title mb-3"> ユーザー情報の編集</h2>
                    <button 
                    onclick="location.href= '{{ url('admin/user') }}'"
                        class="back back-user">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</button>
                </div>
                <div class="card-content mt-4">
                    <div class="card-body">
                        <form id="formEdit" action="{{ route('user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-add-users form-regist">
                                <input type="hidden" name="" id="user-id" value="{{ $user->id }}">
                                <fieldset class="form-group ">
                                    <div class="box d-flex justify-content-between">
                                        {{-- check again  --}}
                                        <div class="d-flex list-school">
                                            <input type="hidden" name="" class="school_code" value="" />
                                            <input type="hidden" name="" class="group_code" />
                                            <label for="" class="d-flex align-items-center">学校名</label>
                                            @if ($user->group == null)
                                                <input name="" id="school" disabled class="bg-transparent input-user"
                                                    value="{{ $user->school->name }}">
                                            @else
                                                <input name="" id="school" disabled class="bg-transparent input-user"
                                                    value="{{ $user->group->name }}">
                                            @endif

                                        </div>
                                        <div class="d-flex">
                                            <label for=""
                                                class="d-flex align-items-center justify-content-center">学校ID</label>
                                            @if ($user->group == null)
                                                <input name="" id="school_code" disabled
                                                    class="bg-transparent code input-user" value="{{ $user->school->code }}">
                                            @else
                                                <input name="" id="school_code" disabled
                                                    class="bg-transparent code input-user" value="{{ $user->group->code }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="d-flex align-items-center">学科名</label>
                                            @if ($user->group == null)
                                                <input name="" id="grade_id" disabled class="bg-transparent input-user"
                                                    value="{{ $user->grade->name }}">
                                            @endif
                                        </div>

                                        <div class="d-flex">
                                            @if ($user->group == null)
                                                <label for=""
                                                    class="d-flex align-items-center justify-content-center grade">学科ID</label>

                                                <input name="" id="grade_code" disabled
                                                    class="bg-transparent code input-user" value="{{ $user->grade->code }}">
                                            @endif

                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">ユーザー名</label>
                                        <div class="d-block">
                                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                <input type="text" name="full_name" id="full_name" maxlength="255"
                                                    value="{{ $user->full_name }}" class="input-user">
                                                <p class="text-danger error-text full_name_error"></p>
                                            @else
                                                <input type="text" name="" id="" maxlength="255"
                                                    value="{{ $user->full_name }}" readonly class="bg-transparent input-user">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">生年月日</label>
                                        <div class="d-block">
                                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                <input name="birthday" id="birthday" class="input-user"
                                                    value="{{ \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') }}"
                                                    readonly>
                                                <p class="text-danger error-text birthday_error"></p>
                                            @else
                                                <input type="text" name="" id=""
                                                    class="bg-transparent input-user" readonly
                                                    value="{{ \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">ユーザーID</label>
                                        <div class="d-block">
                                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                <input type="text" name="student_id" id="student_id"
                                                    class="bg-transparent input-user" readonly
                                                    value="{{ substr($user->student_code, 3, 7) }}">
                                                <p class="text-danger error-text student_id_error"></p>
                                            @else
                                                <input type="text" name="" id=""
                                                    class="bg-transparent input-user" readonly
                                                    value="{{ substr($user->student_code, 3, 7) }}">
                                            @endif
                                        </div>

                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">パスワード</label>
                                        <div class="d-block">
                                            <input type="password" name="" id="password-user"
                                                class="bg-transparent input-user" readonly value="******">
                                            <span class="fa fa-fw fa-eye-slash field_icon show-password mr-2"
                                                data-id="{{ $user->id }}"></span>
                                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                <span type="button" class="change-password"
                                                    id="modal-change-password" data-id="{{ $user->id }}"><svg
                                                        class="align-bottom" xmlns="http://www.w3.org/2000/svg"
                                                        width="14" height="22"
                                                        viewBox="1678.334 435.27 26.988 27.209">
                                                        <g data-name="edit">
                                                            <path
                                                                d="m1688.833 456.875 1.94-1.94h-7.19a1.141 1.141 0 1 1 0-2.283h9.473l5.86-5.861c.252-.251.53-.459.826-.623v-7.8a3.098 3.098 0 0 0-3.098-3.098h-15.213a3.098 3.098 0 0 0-3.097 3.098v18.196a3.098 3.098 0 0 0 3.097 3.097h6.56l.842-2.786Zm-6.391-15.678c0-.633.513-1.141 1.141-1.141h10.91a1.141 1.141 0 0 1 0 2.283h-10.91a1.141 1.141 0 0 1-1.141-1.142Zm0 6.299c0-.634.513-1.142 1.141-1.142h10.91a1.141 1.141 0 0 1 0 2.283h-10.91a1.145 1.145 0 0 1-1.141-1.141Z"
                                                                fill="#8ddae7" fill-rule="evenodd"
                                                                data-name="パス 26" />
                                                            <g data-name="グループ 13">
                                                                <g data-name="グループ 11">
                                                                    <path
                                                                        d="m1695.945 460.18-3.277-3.278 9.008-9.008a1.266 1.266 0 0 1 1.786 0l1.491 1.492a1.266 1.266 0 0 1 0 1.786l-9.008 9.008Z"
                                                                        fill="#8ddae7" fill-rule="evenodd"
                                                                        data-name="パス 27" />
                                                                </g>
                                                                <g data-name="グループ 12">
                                                                    <path
                                                                        d="m1694.552 461.485-4.228.995.994-4.228 3.234 3.233Z"
                                                                        fill="#8ddae7" data-name="パス 28" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </svg></span>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="box">
                                        <div class="d-inline-block">
                                            <label for="" class="mt-1">有効期限</label>
                                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                <input name="expired_at" id="expired_at" style="height: 40px" class="input-user"
                                                    value="{{ \Carbon\Carbon::parse($user->expired_at)->format('d/m/Y') }}"
                                                    readonly>
                                                <p class="text-danger error-text expired_at_error"
                                                    style="padding-left: 7rem"></p>
                                            @else
                                                <input type="text" name="" id=""
                                                    value="{{ \Carbon\Carbon::parse($user->expired_at)->format('d/m/Y') }}"
                                                    readonly class="bg-transparent input-user">
                                            @endif
                                        </div>
                                        @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                            <button type="button" class="float-right btn btn-regist editUser"
                                                id="editUser" OnClientClick ="javascript:history.go(-1);return false;">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
                                        @endif
                                    </div>

                                </fieldset>
                            </div>
                        </form>
                        @include('components.modal-change-password-user')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
    rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"
    integrity="sha256-/H4YS+7aYb9kJ5OKhFYPUjSJdrtV6AeyJOtTkw6X72o=" crossorigin="anonymous"></script>
<script src="/backend/assets/js/account.js"></script>
<script type="text/javascript">
    var key = {!! json_encode(env('APP_KEY')) !!}
    var encrypted = {!! json_encode(substr($user->display_password, 15)) !!}
    var pass = '{{ \Illuminate\Support\Facades\Crypt::decrypt(substr($user->display_password, 15)) }}'
    $(".show-password").click(function(e) {
        // let stripe_key = '{{ env('STRIPE_KEY') }}';
        // console.log(stripe_key);
        var id = $(this).attr('data-id')
        $this = $(this);
        e.preventDefault()
        $.ajax({
            url: '/admin/user/show-password/' + id,
            method: 'GET',
            success: function(data) {
                // $("#password").attr("value", decrypted.toString(CryptoJS.enc.Utf8));
                var input = $('#password-user');
                if (input.attr('type') === 'password') {
                    input.attr({
                        'type': 'text',
                        'value': pass
                    });
                    $this.removeClass("fa-eye-slash").css('color', "#c1c1c1");
                    $this.addClass("fa-eye").css('color', "#000000");
                } else if (input.attr('type') === 'text') {
                    input.attr({
                        'type': 'password',
                        'value': '******'
                    });
                    $this.removeClass("fa-eye").css('color', "#000000");
                    $this.addClass("fa-eye-slash").css('color', "#c1c1c1");
                }
            }
        })
    })
    let date = new Date();
    $("#birthday").datepicker({
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        defaultDate: null,
        endDate: date,
    })
    $("#expired_at").datepicker({
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        format: 'dd/mm/yyyy',
        // defaultDate: null,
        startDate: date,
    })
</script>
@endsection
