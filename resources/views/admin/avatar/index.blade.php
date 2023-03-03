@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('pageTitle', 'Avatars')
@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true" style="font-size: 14px"></i></a>  〉<a href='#'>アバター登録</a></h5>
                        <h2 class="card-title">アバター登録</h2>
                    </div>
                    {{-- <button class="button-back-avatar">戻る</button> --}}
                    <div class="card-content">
                        <div class="card-body">
                            <div id="avatar-grid">
                                @include('admin.avatar.grid')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('components.overlay')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/avatar.js') }}' type="text/javascript"></script>
@endsection
