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
                            href="#" class="text-dark">管理者登録</a></h5>
                    <h2 class="card-title mb-3">管理者登録</h2>
                    <a href="{{ url('admin/mentor') }}"
                        class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="formAdd" action="{{ route('mentor.store') }}" method="POST">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-regist">
                                <fieldset class="form-group ">
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">管理者氏名</label>
                                        <div class="d-block">
                                            <input type="text" name="full_name" id="full_name" maxlength="255">
                                            <p class="text-danger error-text full_name_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">管理者 User ID</label>
                                        <div class="d-block">
                                            <input type="text"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0/, '0');"
                                                name="code" id="code" maxlength="4" />
                                            <p class="text-danger error-text code_error"></p>
                                            <p class="text-danger error-text admin_id_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">管理者メールアドレス</label>
                                        <div class="d-block">
                                            <input type="text" name="email" id="email" maxlength="255">
                                            <p class="text-danger error-text email_error"></p>
                                        </div>
                                    </div>
                                    <div class=" box">
                                        <div class="d-inline-block">
                                            <label for="" class="mt-1">担当権限</label>
                                            <select name="role_id" id="">
                                                @foreach ($role as $key => $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            <p class="text-danger error-text role_id_error" style="padding-left: 11rem">
                                            </p>
                                        </div>
                                        {{-- <p class="text-danger error-text role_id_error"></p> --}}
                                        <button type="button" class="float-right btn btn-regist"
                                            id="addMentor">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
                                    </div>

                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- mentorjs --}}

</div>
@endsection
@section('scripts')
<script src="/backend/assets/js/mentor.js"></script>
@endsection
