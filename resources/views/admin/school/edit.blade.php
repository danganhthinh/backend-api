@extends('layouts.master')
@section('content')
@section('pageTitle', 'Schools')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="{{ url('admin/school') }} ">学校マスタ管理</a> 〉<a
                            href="#" class="text-dark">学校所有者情報の編集</a></h5>
                    <h2 class="card-title mb-3">学校所有者情報の編集</h2>
                    <button onclick="location.href='{{ url('admin/school') }}'"
                        class="back">戻&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;る</button>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="formEdit" action="{{ route('school.update', $school->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 school form-regist">
                                <fieldset class="form-group ">
                                    <input type="hidden" name="" id="school-id" value="{{ $school->id }}">
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1 label-school">学校名</label>
                                            <div class="d-block">
                                                <input type="text" name="name" id="name"
                                                    value="{{ $school->name }}" maxlength="255" class="input-school">
                                                <p class="text-danger error-text name_error"></p>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <label for="" class="text-center mt-le-1 label-school">学校ID</label>
                                            <input type="text" name="code" id="code"
                                                class="bg-transparent input-school" value="{{ $school->code }}"
                                                readonly="readonly" style="margin-right: 2.5rem;" maxlength="3" style="max-width: 256px;">

                                        </div>
                                    </div>
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1 label-school">学科名</label>
                                            <div class="d-block">
                                                <ul class="grade-name">
                                                    @foreach ($school->grade as $grade)
                                                        <li class="mb-2 grade-item" data-id="{{ $grade->id }}"><input
                                                                type="text" class="input-school" name="grade[{{ $grade->id }}][name]"
                                                                value="{{ $grade->name }}" maxlength="255"></li>
                                                        <p class="text-danger error-text grade_{{ $grade->id }}_name_error"
                                                            style="margin: 20px 20px 10px 20px;; height: 33px"></p>
                                                    @endforeach
                                                    {{-- <li class="mb-2" data-id="1"><input type="text"
                                                                name="grade[1][name]"></li> --}}
                                                </ul>
                                                <button type="button"
                                                    style="width: auto; height: auto; margin-bottom: 20px"
                                                    id="edit-field-grade"><i class="fa fa-plus-circle"
                                                        aria-hidden="true" style="padding-right: 1px"></i></button>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <div class="label-code">
                                                @if (count($school->grade) > 0)
                                                    <label for=""
                                                        class="text-center mt-le-1 label-school">学科ID</label>
                                                @else
                                                @endif
                                            </div>
                                            <div class="d-block">
                                                <ul class="grade-code">
                                                    <input id="array_delete_grade" name="graded[]" value=""
                                                        type="hidden">
                                                    @foreach ($school->grade as $grade)
                                                        <li class="mb-2" data-id="{{ $grade->id }}">
                                                            <input type="text" name="" id=""
                                                                value="{{ $grade->code }}" readonly="readonly"
                                                                class="bg-transparent input-school">
                                                            @if ($grade->student == 0)
                                                                <i class="danger fa fa-trash delete-grade ml-2"
                                                                    aria-hidden="true" data-id="{{ $grade->id }}">
                                                                </i>
                                                            @else
                                                                <i class="fa fa-trash ml-2" aria-hidden="true"
                                                                    style=" cursor: not-allowed; color: #c1c1c1">
                                                                </i>
                                                            @endif

                                                            <input type="hidden" name="grade[{{ $grade->id }}][id]"
                                                                id="" value="{{ $grade->id }}"
                                                                id="grade_code">

                                                        </li>
                                                        <p class="text-danger error-text grade_{{ $grade->id }}_code_error"
                                                            style="margin: 20px 20px 10px 20px;; height: 33px; max-width: 256px"></p>
                                                    @endforeach
                                                    {{-- <li class="mb-2" data-id="1"><input type="text"
                                                                name="grade[1][code]" id=""><i
                                                                class="fa fa-trash remove-grade-code ml-2"
                                                                aria-hidden="true"></i></li> --}}
                                                </ul>
                                            </div>
                                        </div>
                                        {{-- <div class="d-flex">
                                                <label for="" class="text-center mt-le-1 ">学科ID</label>
                                                <ol class="grade-code">
                                                    <li class="mb-2"><input type="text" name="grade[1][code]" id=""><button class="remove-grade-code"><i class="fa fa-trash" aria-hidden="true"></i></button></li>
                                                </ol>
                                                
                                            </div> --}}
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">電話番号</label>
                                        <div class="d-block">
                                            <input type="text" name="phone_number" id="phone_number"
                                                value="{{ $school->phone_number }}" maxlength="11"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0/, '0');"
                                                class="input-school">
                                            <p class="text-danger error-text phone_number_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">メールアドレス</label>
                                        <div class="d-block">
                                            <input type="text" name="email_in_charge" id="email_in_charge"
                                                value="{{ $school->email_in_charge }}" maxlength="255"
                                                class="input-school">
                                            <p class="text-danger error-text email_in_charge_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1 label-school">代表者氏名</label>
                                        <div class="d-block">
                                            <input type="text" name="name_represent" id="name_represent"
                                                value="{{ $school->name_represent }}" maxlength="255"
                                                class="input-school">
                                            <p class="text-danger error-text name_represent_error"></p>
                                        </div>
                                    </div>
                                    <div class="box">
                                        <div class="d-inline-block">
                                            <label for="" class="label-school"
                                                style="margin-right: 14px">担当者氏名</label>
                                                
                                                <select name="admin[]" id="admin" multiple
                                                data-maximum-selection-length="10" class="input-school">
                                                <?php $id_array = []; ?>
                                                @foreach ($school->mentor as $mentor)
                                                    {{-- @if ($value->id == $mentor->mentor->id) --}}
                                                    <option value="{{ $mentor->mentor->id }}" selected>
                                                        {{ $mentor->mentor->full_name }}</option>
                                                    {{ array_push($id_array, $mentor->mentor->id) }}
                                                    {{-- @endif --}}
                                                @endforeach
                                                @foreach ($admin as $value)
                                                    @if (!in_array($value->id, $id_array))
                                                        <option value="{{ $value->id }}">{{ $value->full_name }}
                                                        </option>
                                                    @endif
                                                @endforeach


                                            </select>
                                            <p class="text-danger error-text admin_error admin-school-error">
                                            </p>

                                        </div>

                                        <button type="submit" class="float-right btn btn-regist"
                                            id="editSchool">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録</button>
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
