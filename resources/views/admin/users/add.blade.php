@extends('layouts.master')
@section('content')
@section('pageTitle', 'Users')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true" style="font-size: 14px"></i></a>  〉<a href="{{ url('admin/user') }}"> ユーザー登録</a> 〉 <a href="#" class="text-dark">ユーザー登録</a></h5>
                    <h2 class="card-title mb-3">ユーザー登録</h2>
                    <a href="{{ url('admin/user') }}"
                    class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</a>
                </div>
                <div class="card-content mt-4">
                    <div class="card-body">
                        <form id="formAdd" action="{{ route('user.store') }}" method="POST">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-add-users form-regist">
                                <fieldset class="form-group ">
                                    <div class="box d-flex justify-content-between">
                                        {{-- check again  --}}
                                        <div class="d-flex list-school">
                                            <input type="hidden" name="" class="school_code" value="" />
                                            <input type="hidden" name="" class="group_code" />
                                            <label for="" class="mt-le-1">学校名</label>
                                            <div class="d-block">
                                                <select name="school_group" id="school" class="school-name span-ellipsis input-user " required>
                                                    <option value=""></option>
                                                    <optgroup label="School">
                                                        @foreach ($school as $item_school)
                                                            <option data-type="school" value="{{ $item_school->id }}"
                                                                data-school-id="{{ $item_school->id }}"
                                                                data-school-code="{{ $item_school->code }}"
                                                                data-school-name="{{ $item_school->name }}">
                                                                {{ Str::limit($item_school->name,30)}}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="Group">
                                                        @foreach ($group as $item_group)
                                                            <option data-type="group" value="{{ $item_group->id }}"
                                                                data-group-id="{{ $item_group->id }}"
                                                                data-group-code="{{ $item_group->code }}"
                                                                data-group-name="{{ $item_group->name }}">
                                                                {{ Str::limit($item_group->name,30) }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                                <p class="text-danger error-text school_error"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1">学校ID</label>
                                            <input type="text" name="" class="bg-transparent code input-user"
                                                id="school_code" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1">学科名</label>
                                            <div class="d-block">
                                                <select name="grade_id" id="grade_id" disabled class="bg-transparent span-ellipsis input-user"
                                                    style="background-image: none">
                                                </select>
                                                <p class="text-danger error-text grade_id_error"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1">学科ID</label>
                                            <input type="text" name="" class="bg-transparent code input-user"
                                                id="grade_code" value="" readonly>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">ユーザー名</label>
                                        <div class="d-block">
                                            <input type="text" name="full_name" id="full_name" maxlength="255" class="input-user">
                                            <p class="text-danger error-text full_name_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">生年月日</label>
                                        <div class="d-block">
                                            <input name="birthday" id="birthday" readonly class="input-user">
                                            <p class="text-danger error-text birthday_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">ユーザーID</label>
                                        <div class="d-block">
                                            <input type="text" class="input-user" name="student_id" id="student_id" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0/, '0');">
                                            <p class="text-danger error-text student_id_error"></p>
                                        </div>

                                    </div>
                                    <div class="box">
                                        <div class="d-inline-block">
                                            <label for="" class="mt-le-1">有効期限</label>
                                            <input name="expired_at" id="expired_at" readonly class="input-user "
                                                style="height: 40px">
                                            <p class="text-danger error-text expired_at_error" style="padding-left: 7.3rem"></p>
                                        </div>
                                        <button type="button" class="float-right btn btn-regist addUser"
                                            id="addUser">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
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
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
    rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
<script src="/backend/assets/js/account.js"></script>
<script>
    let date = new Date();
    $("#birthday").datepicker({
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        format: "dd/mm/yyyy",
        // format: 'yyyy/mm/dd',
        endDate: date,
    });
    $("#expired_at").datepicker({
        changeMonth: true,
        changeYear: true,
        autoclose: true,
        format: "dd/mm/yyyy",
        // format: 'yyyy/mm/dd',
        startDate: date,
    });
</script>

@endsection
