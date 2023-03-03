@extends('layouts.master')
@section('content')
@section('pageTitle', 'Groups')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="{{ url('admin/group') }}">団体マスタ管理</a>　〉<a
                            href="#" class="text-dark">団体マスタ登録</a></h5>
                    <h2 class="card-title mb-3">団体マスタ登録</h2>
                    <a href="{{ url('/admin/group') }}"
                        class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="formAdd" action="{{ route('group.store') }}" method="POST">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-regist">
                                <fieldset class="form-group ">
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">企業（団体名）</label>
                                        <div class="d-block">
                                            <input type="text" name="name" id="name" maxlength="255">
                                            <p class="text-danger error-text name_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">団体ID</label>
                                        <div class="d-block">
                                            <input type="text" name="code" id="code" maxlength="3"
                                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')">
                                            <p class="text-danger error-text code_error"></p>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <label for="" class="">種別</label>
                                        <select name="group_type" id="">
                                            @foreach ($group_type as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">電話番号</label>
                                        <div class="d-block">
                                            <input type="text" name="phone_number" id="phone_number"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0/, '0');"
                                                maxlength="11">
                                            <p class="text-danger error-text phone_number_error"></p>
                                        </div>

                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">メールアドレス</label>
                                        <div class="d-block">
                                            <input type="text" name="email_in_charge" id="email_in_charge"
                                                maxlength="255">
                                            <p class="text-danger error-text email_in_charge_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-1">代表者氏名</label>
                                        <div class="d-block">
                                            <input type="text" name="name_represent" id="name_represent"
                                                maxlength="255">
                                            <p class="text-danger error-text name_represent_error"></p>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="d-inline-block">
                                            <label for="" class=""
                                                style="margin-right: 14px">担当者氏名</label>
                                            <select name="admin[]" id="admin" multiple
                                                data-maximum-selection-length="10">
                                                @if ($admin->isEmpty())
                                                    <option disabled>どの学校/団体にまだ所属しない教師</option>
                                                @else
                                                    @foreach ($admin as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->full_name }}
                                                        </option>
                                                    @endforeach
                                                @endif

                                            </select>
                                            <p class="text-danger error-text admin_error" style="padding-left: 11rem">
                                            </p>
                                        </div>
                                        <button type="button" class="float-right btn btn-regist"
                                            id="addGroup">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
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
<script src="/backend/assets/js/group.js"></script>
@endsection
