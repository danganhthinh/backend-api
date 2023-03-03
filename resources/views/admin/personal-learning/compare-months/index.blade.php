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
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home"
                                    aria-hidden="true" style="font-size: 14px"></i></a> 〉<a href='#'>学習分析</a></h5>
                        <div style="display:flex;min-width: 100%; width:1500px">
                            <h2 class="card-title" style="width:90%">個別学習分析</h2>
                            <a id="compare-learning-print" class="btn regist btn-print-learning" style="color:white"><img
                                    width=25 src="/backend/images/icons/print.png">&nbsp;印刷</a>
                        </div>
                    </div>
                    <button class="button-back-avatar" onclick="location.href='{{ url('/admin/learning/') }}'">戻る</button>
                    <div class="card-content">
                        <div class="card-body">
                            <div id="learning-compare-data">
                                {{-- @include('admin.personal-learning.compare-months.grid') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.overlay')
    @endsection
    @section('scripts')
    <script>
        window.onload = function() {
            let account_id = localStorage.getItem('learning-compare-account_id');
            let year = localStorage.getItem('learning-compare-year');
            $.ajax({
                async: false,
                global: true,
                method: "get",
                url: "/admin/learning/comparison-month/" + account_id + '/' + year,
                success: function(data) {
                    $('#learning-compare-data').html(data);
                }
            })
        }
        
    $(document).on('change', '#learning-select-month1', function () {
        data_by_month();
        console.log('aaa');

    })
    $(document).on('change', '#learning-select-month2', function () {
        data_by_month();
    })

    function data_by_month() {
        let month1 = $('#learning-select-month1').val();
        let month2 = $('#learning-select-month2').val();
        let account_id = $('#accound-id-compare').val();
        let year = $('#year-compare').val();
        $.ajax({
            async: false,
            global: true,
            method: "get",
            data: {
                'month1': month1.substring(3, 5),
                'month2': month2.substring(3, 5),
            },
            url: "/admin/learning/comparison-month/" + account_id + '/' + year,
            success: function (data) {
                $('#learning-compare-data').empty().html(data);
            }
        })
    }
    </script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> --}}
    {{-- <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> --}}
    {{-- <script src='{{ asset('assets/js/admin/functions/chart.js') }}' type="text/javascript"></script> --}}
    {{-- <script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script> --}}
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
    {{-- <script src='{{ asset('assets/js/admin/compare-learning-analysis.js') }}' type='text/javascript'></script> --}}
@endsection
