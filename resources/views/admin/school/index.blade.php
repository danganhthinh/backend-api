@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('content')
@section('pageTitle', 'Schools')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true" style="font-size: 14px"></i></a>  〉<a href="#" class="text-dark">学校マスタ管理</a>
                        <h2 class="card-title">学校マスタ管理</h2>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button class="btn mb-3 text-white btn-add" onclick="window.location='{{ route('school.create') }}'">学&nbsp;&nbsp;校&nbsp;&nbsp;マ&nbsp;&nbsp;ス&nbsp;&nbsp;タ&nbsp;&nbsp;登&nbsp;&nbsp;録</button>
                            <form action="" id="formSearch" method="get" class="mr-1">
                                <div class="col-xl-2 col-lg-6 col-md-12 mb-1 d-flex form-search-name">
                                    <fieldset class="form-group">
                                        <input type="text" class="form-control" id="searchSchool" name="searchKey"
                                            placeholder="">
                                        <button id="btnSearchSchool" class="btn btn-search"><i
                                                class="fa fa-search"></i></button>
                                    </fieldset>
                                </div>
                            </form>
                        </div>
                        <div id="gird-school">
                            {{-- @include('admin.school.grid') --}}
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
<script src="/backend/assets/js/school-index.js"></script>
<script src="{{ asset('backend/assets/js/school.js') }}"></script>
<script>
    if (localStorage.getItem('item_success') == '1') {
        success_toast('成功しました')
        localStorage.removeItem("item_success");
    }
</script>
@endsection
