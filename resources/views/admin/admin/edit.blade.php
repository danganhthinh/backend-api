@extends('layouts.master')
@section('content')
@section('pageTitle', 'Mentors')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="{{ url('admin/mentor') }}">管理者一覧</a>　〉<a
                            href="#" class="text-dark">管理者情報を編集する</a></h5>
                    <h2 class="card-title mb-3">管理者情報を編集する</h2>
                    <a href="{{ url('admin/mentor') }}"
                        class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="formEdit" action="{{ route('mentor.update', $admin->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 mentor form-regist">
                                <fieldset class="form-group ">
                                    <input type="hidden" name="" id="mentor-id" value="{{ $admin->id }}">
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">学校・団体名</label>
                                        <div class="d-block">
                                            <input type="text" name="" id="" class="bg-transparent"
                                                readonly
                                                value="@foreach ($institution as $key => $value){{ $value->name }} @endforeach">
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">管理者氏名</label>
                                        <div class="d-block">
                                            <input type="text" name="full_name" id="full_name"
                                                value="{{ $admin->full_name }}" maxlength="255">
                                            <p class="text-danger error-text full_name_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">管理者 User ID</label>
                                        <div class="d-block">
                                            <input type="text" name="code" id="code"
                                                value="{{ $admin->student_code }}" readonly class="bg-transparent">
                                            <p class="text-danger error-text code_error"></p>
                                            <p class="text-danger error-text admin_id_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">管理者メールアドレス</label>
                                        <div class="d-block">
                                            <input type="text" name="email" id="email"
                                                value="{{ $admin->email }}" maxlength="255">
                                            <p class="text-danger error-text email_error"></p>
                                        </div>
                                    </div>
                                    <div class=" box">
                                        <div class="d-inline-block">
                                            <label for="" class="">担当権限</label>
                                            <input type="text" name="role_id" id="" disabled
                                                value="{{ $admin->role_name }}">
                                            <p class="text-danger error-text role_id_error"></p>
                                        </div>
                                        <button type="button" class="float-right btn btn-regist"
                                            id="editMentor">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
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
<script src="/backend/assets/js/mentor.js"></script>
@endsection
