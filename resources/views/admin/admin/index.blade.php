@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('content')
@section('pageTitle', 'Mentors')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home"
                                aria-hidden="true" style="font-size: 14px"></i></a> 〉<a href="#"
                            class="text-dark">管理者一覧</a></h5>
                    <h2 class="card-title">管理者一覧</h2>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button class="btn mb-3 text-white btn-add"
                                onclick="window.location='{{ route('mentor.create') }}'">管&nbsp;&nbsp;理&nbsp;&nbsp;者&nbsp;&nbsp;登&nbsp;&nbsp;録</button>
                            <form action="" id="formSearch" method="get" style="width: 414px;">
                                <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-search-name">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control" id="searchMentor" name="search"
                                            placeholder="" value="">
                                        <button id="btnSearchMentor" class="btn btn-search"><i
                                                class="fa fa-search"></i></button>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                        <div id="gird-mentor">
                            {{-- @include('admin.admin.grid') --}}
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
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
<script>
    $(document).ready(function() {
        let keyRemove = [
            "current_group_page",
            "current_school_page",
            "current_user_page",
            "searchGroup",
            "searchSchool",
            "school_id",
            "group_id",
            "grade_id",
            "searchKey"
        ];
        for (key of keyRemove) {
            localStorage.removeItem(key);
        }
        localStorage.removeItem("current_school_page");
        localStorage.removeItem("current_group_page");
        localStorage.removeItem("searchSchool");
        localStorage.removeItem("searchGroup");
        localStorage.removeItem("school_id");
        localStorage.removeItem("grade_id");
        localStorage.removeItem("searchKey")
        if (localStorage.getItem('item_success') == '1') {
            success_toast('成功しました')
            localStorage.removeItem("item_success");
        }
        if (localStorage.getItem("check-back-mentor")) {
            localStorage.removeItem("check-back-mentor");
            if (localStorage.getItem("searchMentor")) {
                $searchKey = localStorage.getItem("searchMentor");
                $("#searchMentor").val($searchKey);
                // localStorage.setItem("current_mentor_page", 1)
            }
            if (localStorage.getItem("current_mentor_page")) {
                $page = localStorage.getItem("current_mentor_page");
                $("#hidden_page").val($page);
                // localStorage.setItem("current_mentor_page", 1)
            } else {
                $("#hidden_page").val(1);
            }
        }
        ajaxsearch();
        $(document).on("click", "#edit-mentor", function(e) {
            e.preventDefault();
            localStorage.setItem("check-back-mentor", "1");
            history_back();
            var id = $(this).attr("data-id");
            window.location.href = "/admin/mentor/" + id + "/edit";
        });
        $(document).on("click", ".btn-add", function(e) {
            e.preventDefault();
            localStorage.setItem("check-back-mentor", "1");
            history_back();
            window.location.href = "/admin/mentor/create";
        });
    })
</script>
<script src="{{ asset('backend/assets/js/mentor.js') }}"></script>
@endsection
