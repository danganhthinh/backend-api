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
                    <div class="card-header">
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true" style="font-size: 14px"></i></a>  〉<a href='#'>学習分析</a></h5>
                        <h2 class="card-title">学習分析</h2>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                            </ul>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div>
                                <form action="" class="form-filter-learning" style=' box-shadow: inset 2px 3px 2px #e1e1e1;'>
                                    <div style="width:30%">
                                        <select class="learning-select-school learning-select-school-background learning-ellipsis" id="learning-select-school">
                                            <optgroup label="Schools">
                                                @foreach ($schools as $school)
                                                    <option data-type="school" value="{{ $school->id }}">{{ Str::limit($school->name,20) }}&emsp;</option>
                                                @endforeach
                                            <optgroup label="Groups">
                                                @foreach ($groups as $group)
                                                    <option data-type="group" value="{{ $group->id }}">{{ Str::limit($group->name,20) }}&emsp;</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div style="width:35%;padding-left: 2%;">
                                        <select class="learning-select-school learning-select-school-background learning-ellipsis" style="padding-left: 10%;" name=""
                                            id="learning-select-grade">
                                        </select>
                                    </div>
                                    <span style="width:10%" class='learning-select-span'>クラス</span>
                                    <div style="width:15%">
                                        <input class="learning-select-school learning-select-school-background" style="padding-left: 1%;text-align: center;" name=""
                                            id="learning-select-school_year" value="2022">
                                    </div>
                                    <span style="width:10%" class='learning-select-span'>年度</span>
                                </form>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <form action="" id="formSearch" method="post" style="width: 60%;">
                                    <div class="form-search-learning">
                                        <fieldset class="form-group">
                                            <label class="label-search-learning">個人検索</label>
                                            <input type="text" class="form-control learning-inputs"
                                                style="width: 81%; border-bottom-left-radius: 25px; border-top-left-radius: 25px;box-shadow: inset 2px 3px 2px #d1d1d1;"
                                                id="learning-search" name="search"
                                                placeholder="&nbsp;&nbsp;&nbsp;&nbsp;氏&nbsp;&nbsp;&nbsp;&nbsp;名">
                                            <a class="btn btn-search" href="javascript:void(0)" id="btnSearch-learning"
                                                style="margin-top:0"><i class="fa fa-search"></i>&nbsp;&nbsp;検索</a>
                                        </fieldset>
                                    </div>
                                </form>
                            </div>
                            <div id="learning-data">
                                {{-- @include('admin.learning.grid') --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.overlay')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
    <script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/learning-analysis.js') }}' type="text/javascript"></script>
@endsection
