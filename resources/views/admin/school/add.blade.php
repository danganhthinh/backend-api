@extends('layouts.master')
@section('content')
@section('pageTitle', 'Schools')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="{{ url('admin/school') }}">学校マスタ登録</a>　〉<a
                            href="#" class="text-dark">学校マスタ登録</a> </h5>
                    <h2 class="card-title mb-3">学校マスタ登録</h2>
                    <a href="{{ url('admin/school') }}"
                        class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</a>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="formAdd" action="{{ route('school.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 school form-regist">
                                <fieldset class="form-group">
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1 label-school">学校名</label>
                                            <div class="d-block">
                                                <input type="text" name="name" id="name" maxlength="255"
                                                    class="input-school">
                                                <p class="text-danger error-text name_error"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <label for="" class="text-center mt-le-1 label-school">学校ID</label>
                                            <div class="d-block">
                                                <input type="text" name="code" id="code"
                                                    style="margin-right: 30px;" maxlength="3" class="input-school"
                                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')">
                                                <p class="text-danger error-text code_error" style="max-width: 256px;">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1 label-school">学科名</label>
                                            <div class="d-block">

                                                <ul class="grade-name">

                                                </ul>
                                                <button type="button"
                                                    style="width: auto; height: auto; margin-bottom: 20px;"
                                                    id="add-field-grade"><i class="fa fa-plus-circle" aria-hidden="true"
                                                        style="padding-right: 1px"></i></button>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="label-code "></div>
                                            <div class="d-block">
                                                <ul class="grade-code ">

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">電話番号</label>
                                        <div class="d-block">
                                            <input type="text" name="phone_number" id="phone_number" maxlength="11"
                                                class="input-school"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0/, '0');">
                                            <p class="text-danger error-text phone_number_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">メールアドレス</label>
                                        <div class="d-block">
                                            <input type="text" name="email_in_charge" id="email_in_charge"
                                                class="input-school" value="" maxlength="255">
                                            <p class="text-danger error-text email_in_charge_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">代表者氏名</label>
                                        <div class="d-block">
                                            <input type="text" name="name_represent" id="name_represent"
                                                class="input-school" maxlength="255">
                                            <p class="text-danger error-text name_represent_error"></p>
                                            {{-- <p class="text-danger error-text name_represent_error">{!! $errors->first('name_represent') !!}</p> --}}
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="d-inline-block">
                                            <label for="" class="label-school"
                                                style="margin-right: 14px">担当者氏名</label>
                                            <select name="admin[]" id="admin" multiple
                                                data-maximum-selection-length="10" class="input-school">
                                                @if ($admin->isEmpty())
                                                    <option disabled>どの学校/団体にまだ所属しない教師</option>
                                                @else
                                                    @foreach ($admin as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->full_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <p class="text-danger error-text admin_error admin-school-error">
                                            </p>
                                        </div>
                                        <button type="submit" class="float-right btn btn-regist"
                                            id="addSchool">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
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
<script src="/backend/assets/js/school.js"></script>

@endsection
