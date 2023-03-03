@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('content')
@section('pageTitle', 'Groups')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home"
                                aria-hidden="true" style="font-size: 14px"></i></a> 〉<a href="#"
                            class="text-dark">団体マスタ管理</a></h5>
                    <h2 class="card-title">団体マスタ管理</h2>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button class="btn mb-3 text-white btn-add"
                                onclick="window.location='{{ route('group.create') }}'">団&nbsp;&nbsp;体&nbsp;&nbsp;マ&nbsp;&nbsp;ス&nbsp;&nbsp;タ&nbsp;&nbsp;登&nbsp;&nbsp;録</button>
                            <form action="" id="formSearch" method="post" style="width: 414px;">
                                <div class="col-xl-2 col-lg-6 col-md-12 mb-1 d-flex form-search-name">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control" id="searchGroup"
                                            name="search"placeholder="">
                                        <button id="btnSearchGroup" class="btn btn-search"><i
                                                class="fa fa-search"></i></button>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                        <div id="gird-group">
                            {{-- @include('admin.group.grid') --}}
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
            "current_mentor_page",
            "current_school_page",
            "current_user_page",
            "searchSchool",
            "searchMentor",
            "school_id",
            "group_id",
            "grade_id",
            "searchKey"
        ];
        for (key of keyRemove) {
            localStorage.removeItem(key);
        }
        if (localStorage.getItem('item_success') == '1') {
            success_toast('成功しました')
            localStorage.removeItem("item_success");
        }
        if (localStorage.getItem("check-back-group")) {
            localStorage.removeItem("check-back-group");
            if (localStorage.getItem("searchGroup")) {
                $searchKey = localStorage.getItem("searchGroup");
                $("#searchGroup").val($searchKey);
                localStorage.setItem("current_group_page", 1)
            }
            if (localStorage.getItem("current_group_page")) {
                $page = localStorage.getItem("current_group_page");
                $("#hidden_page").val($page);
            } else {
                $("#hidden_page").val(1);
            }
        }
        ajaxsearch();

        $(document).on("click", "#edit-group", function(e) {
            e.preventDefault();
            localStorage.setItem("check-back-group", "1");
            history_back();
            var id = $(this).attr("data-id");
            window.location.href = "/admin/group/" + id + "/edit";
        });
        $(document).on("click", ".btn-add", function(e) {
            e.preventDefault();
            localStorage.setItem("check-back-group", "1");
            history_back();
            window.location.href = "/admin/group/create";
        });
    })
</script>
<script src="{{ asset('backend/assets/js/group.js') }}"></script>
@endsection
