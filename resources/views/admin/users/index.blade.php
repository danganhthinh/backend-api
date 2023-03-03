@extends('layouts.master')
{{-- @section('head')

    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection --}}


@section('content')
@section('pageTitle', 'Users')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position">
                        <a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉<a href="#" class="text-dark">ユーザー登録</a>
                    </h5>
                    <h2 class="card-title">ユーザー一覧</h2>

                </div>
                <div class="card-content">
                    <div class="card-body">
                        {{-- <div class="card-form"> --}}
                        <div class="row justify-content-between">
                            <form id="formSearchAccount" class="form-search">
                                <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-search-users">
                                    <fieldset class="form-group info-box-users" style="flex: 2 1 0%">
                                        <div class="info-up d-flex">
                                            <div class="info d-flex">
                                                <label for="school-name">学校名</label>
                                                <select value='' id="school-name" name="" class="input-search">
                                                    <option value=""></option>
                                                    <optgroup label="Schools">
                                                        @foreach ($listSchool as $item_school)
                                                            <option data-type="school" value="{{ $item_school->id }}">
                                                                {{ Str::limit($item_school->name, 30) }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                    <optgroup label="Groups">
                                                        @foreach ($listGroup as $item_group)
                                                            <option data-type="group" value="{{ $item_group->id }}">
                                                                {{ Str::limit($item_group->name, 30) }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                </select>
                                            </div>

                                            <div class="info d-flex">
                                                <label for="school-year">年&nbsp;&nbsp;&nbsp;度</label>
                                                <input type="text" class="form-control bg-white input-search"
                                                    id="school_year_id" placeholder="" name="schoolYear" value="2022"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="info-down d-flex">
                                            <div class="info d-flex">
                                                <label for="grade">所&nbsp;&nbsp;&nbsp;属</label>
                                                <select name="grade_id" id="grade" class="input-search" disabled value=''>

                                                </select>
                                            </div>
                                            <div class="info d-flex">
                                                <label for="username">個&nbsp;&nbsp;&nbsp;人</label>
                                                <input type="text" class="form-control input-search" id="username"
                                                    placeholder="" name="searchKey" value="">
                                            </div>
                                            <div class="tb-btn-search d-flex">
                                                <button id="btnSearchAccount" class="btn btn-search"><i
                                                        class="fa fa-search"
                                                        aria-hidden="true"></i>&nbsp;&nbsp;検索</button>
                                            </div>
                                        </div>


                                    </fieldset>
                                </div>

                            </form>
                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                <div
                                    class="col-xl-2 col-lg-6 col-md-12 mb-1 d-flex form-add justify-content-end mw-100">
                                    <button id="addMultipleUser" class="btn mt-auto btn-add-multiple"
                                        style="">一括登録</button>
                                    @include('components.modal-add-multiple-users')
                                    {{-- <form action="" id="add-single" method="post" class=""> --}}
                                    <a href="{{ url('admin/user/create') }}" id="btnAddSingle"
                                        class="mt-auto btn btn-add-single">個別登録</a>
                                    {{-- </form> --}}
                                </div>
                            @endif

                        </div>
                        {{-- </div> --}}
                        <div id="gird-user">
                            {{-- @include('admin.users.grid') --}}
                        </div>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@include('components.overlay')
@endsection
@section('scripts')
{{-- <script>
        $(document).ready(function() {
        let inputFile = document.getElementById('btnAddMultiple');
        let fileNameField = document.getElementById('add-multiple-name');
            $(document).on('change', function(event) {
                let uploadFileName = event.target.files[0].name;
                fileNameField.textContent = uploadFileName;
            })
        })
        
    </script> --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet"> --}}
{{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
{{-- <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
<script src="/backend/assets/js/account-index.js"></script>
<script src="/backend/assets/js/account.js"></script>
<script>
    if (localStorage.getItem('item_success') == '1') {
        success_toast('成功しました')
        localStorage.removeItem("item_success");
    }
    let current_year = new Date().getFullYear().toString();
    $("#school_year_id").datepicker({
        changeYear: true,
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        autoclose: true,
        startDate: "2022",
        endDate: current_year,
    });
    // $school_id = localStorage.getItem("school_id");
    // $group_id = localStorage.getItem("group_id");
    // $grade = localStorage.getItem("grade_id");
    // $schoolYear = localStorage.getItem("schoolYear");
    // $searchKey = localStorage.getItem("searchKey");
    // // window.location.href = "/admin/user";
    // $page = localStorage.getItem("current_user_page")
    // $.ajax({
    //     url: "/admin/user/search",
    //     data: {
    //         school_id: $school_id,
    //         group_id: $group_id,
    //         schoolYear: $schoolYear,
    //         grade_id: $grade,
    //         searchKey: $searchKey,
    //         page: $page
    //     },
    //     success: function(data) {
    //         if (grade == "") {
    //             $("#grade").prop("disabled", true)
    //         } else {
    //             $("#grade").prop("disabled", false)
    //         }
    //         $("#gird-user").html(data)
    //     }
    // })
</script>
{{-- <script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script> --}}
@endsection
