@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('pageTitle', 'Learning Analysis')
@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true" style="font-size: 14px"></i></a>  〉<a href='#'>学習分析</a></h5>
                        <div style="display:flex;min-width: 100%; width:1500px">
                            <h2 class="card-title" style="width:90%">個別学習分析</h2>
                            <a id="compare-learning-print" class="btn regist btn-print-learning" style="color:white"><img width=25
                                src="/backend/images/icons/print.png">&nbsp;印刷</a>
                        </div>
                    </div>
                    <button class="button-back-avatar" onclick="location.href='{{ url('/admin/learning/') }}'">戻る</button>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="gird">
                                @include('admin.personal-learning.grid')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.overlay')
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
<script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
<script src='{{ asset('assets/js/admin/personal-learning-analysis.js') }}' type="text/javascript"></script>