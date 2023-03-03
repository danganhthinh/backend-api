@extends('layouts.master')
{{-- @section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection --}}
@section('pageTitle', 'Videos')
@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                    style="font-size: 14px"></i></a> 〉<a href='#'>動画ライブラリー管理 〉動画ライブラリー管理</a></h5>
                        <div style="display:flex">
                            <h2 class="card-title" style="width:90%">動画ライブラリー管理</h2>
                            {{-- <a id="btn-mass-add" class="btn regist btn-mass-add" style="color:white">一括登録</a>
                            @include('components.modal-mass-import') --}}
                        </div>
                    </div>
                    <div class="card-content">
                        {{-- <div class="registration-date">
                            登録日2022 / 10 / 22
                        </div> --}}
                        <div class="card-body">
                            <div class="card-form justify-content-between">
                                <div class="row">
                                    <form id="form-add-question" style="min-width: fit-content; width:100%" class="form-vr scroll_to"
                                        action="" method="GET">
                                        <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-add-vr">
                                            <fieldset class="form-group info-box-vr d-flex"
                                                style="margin-right: -19px;width: 1465px; height:323px">
                                                <div class="add-vr" style="width: 814px; margin-right:2px">
                                                    <div class="d-flex add-video">
                                                        <label for=""
                                                            class="add-2D-title add-video-title">動画</label>
                                                        <div class="info-file info-2D-file" style=" margin-left: -198px;">
                                                            <input type="file" accept=".mp4" class="vr"
                                                                name="video" id="question_media">
                                                            <label class="label-choose-file"
                                                                for="question_media">ファイルを選択</label>
                                                            <div class="file-name"
                                                                style="margin-top: 239px; margin-left: 3px;"
                                                                id="question_media_name"></div>
                                                            <p class="error-text" id="question-error-media"
                                                                style="margin-left: 2px; margin-top: -266px; width:267px">
                                                                &nbsp;
                                                            </p>
                                                        </div>
                                                        <img id="illustration_preview" class="preview-video"
                                                            src='/backend/images/icons/default.png' alt=""
                                                            height="150px" width="200px">
                                                        <video id="video_preview" width='200px' height='150px'
                                                            class="preview-video" controls
                                                            style="display:none; border-radius: 16px; background-color:black">
                                                            <source id="video_preview_src" src='' type='video/mp4'>
                                                        </video>
                                                    </div>
                                                    <div class="d-flex add-video" style="margin-left:20px">
                                                        <label for="" class="add-2D-title"
                                                            style="letter-spacing:0;margin-left: 62px;">サムネイル</label>
                                                        <div class="info-file info-2D-file" style=" margin-left: -121px;">
                                                            <input type="file" accept=".jpeg,.png,.jpg" class="vr"
                                                                name="thumbnail" id="video_thumbnail">
                                                            <label class="label-choose-file"
                                                                for="video_thumbnail">ファイルを選択</label>
                                                            <div class="file-name"
                                                                style="margin-top: 239px; margin-left: 3px;"
                                                                id="question_thumbnail_name"></div>
                                                            <p class="error-text" id="question-error-thumbnail"
                                                                style="margin-left: 1%; margin-top: -265px; width:276px">
                                                                &nbsp;</p>
                                                        </div>
                                                        <img id="thumbnail_preview" class="preview-video"
                                                            style="margin-left:157px"
                                                            src='/backend/images/icons/default.png' alt=""
                                                            height="150px" width="200px">
                                                    </div>
                                                </div>
                                                <div class="mass-registration"
                                                    style="margin-top:-8px; width: 563px;margin-left: 26px; padding-bottom:0">
                                                    <div class="info-regist" style="width: 300px;">
                                                        <div class="publishing-settings" style="margin-bottom: -17px;">
                                                            <label class="question-option-label"
                                                                style="letter-spacing: 16px;">動画</label>
                                                            <input maxlength="255" name="title" id="video-title-input"
                                                                class="video-title-input" style="width: 200px;">
                                                        </div>
                                                        <p class="error-text" id="question-error-title"
                                                            style="margin-left: 91px;">&nbsp;</p>
                                                        <div class="publishing-settings" style="margin-top: 2%;">
                                                            <label class="question-option-label"
                                                                for="">公開設定</label>
                                                            <select name="status" id="status" class="status-2D"
                                                                style="width: 200px;">
                                                                <option value="1" class="text-center">公開</option>
                                                                <option value="0" class="text-center">非公開
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="person-in-charge" style="margin-top: 8%;">
                                                            <label class="question-option-label"
                                                                for="">担当者名</label>
                                                            <input hidden class="question-add-account_id"
                                                                name="account_id" id="account_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <input readonly="readonly" class="question-add-account_id"
                                                                style="width: 200px;" name="account_name"
                                                                id="account_name" value="{{ Auth::user()->full_name }}">
                                                        </div>
                                                        <div class="publishing-settings" style="margin-top: 8%;">
                                                            <label class="question-option-label" for=""
                                                                style="letter-spacing: 16px;">科目</label>
                                                            <select name="subject_id" id="subject_id"
                                                                style="width: 200px;" class="question-level">
                                                                @foreach ($subject as $sub)
                                                                    <option value="{{ $sub->id }}"
                                                                        class="text-center">
                                                                        {{ $sub->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="publishing-settings" style="margin-top: 8%;">
                                                            <label class="question-option-label" for=""
                                                                style="letter-spacing: 5px;">レベル</label>
                                                            <select name="video_level" id="video_level"
                                                                style="width: 200px;" class="question-level">
                                                                @for ($i = 1; $i < 4; $i++)
                                                                    <option value="{{ $i }}"
                                                                        class="text-center">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <input id="question_type" name="question_type" value="4"
                                                            hidden>
                                                    </div>
                                                    <div style="width: 204px; margin-top:-100px; margin-right:-10px"
                                                        id="submit-cancel-buttons">
                                                        <a id="btn-cancel-question" class="btn regist"
                                                            style="box-shadow: 6px 5px 13px #c1c1c1 inset; background-color: #a1a1a1; margin-left: 19px; width:175px; padding-top: 25px; height: 74px;">キャンセル</a>
                                                        <a id="btn-store-question" class="btn regist"
                                                            style="margin-top:29px; margin-left: 19px; width:175px; padding-top: 25px; height: 74px;">追
                                                            加 登 録</a>
                                                        <a id="btn-update-video" data-id="" class="btn regist"
                                                            style="margin-top:29px; margin-left: 19px; width:175px; padding-top: 25px; height: 74px; display:none">アップデート</a>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-form justify-content-between filter-video-container">
                                <div class="publishing-settings filter-videos">
                                    <label class="question-option-label" for=""
                                        style="letter-spacing: 16px;">科目</label>
                                    <select name="video-subject-filter" id="video-subject-filter"
                                        class="video-subject-filter">
                                        <option value="" class="text-center"></option>
                                        @foreach ($subject as $sub)
                                            <option value="{{ $sub->id }}" class="text-center">
                                                {{ $sub->name }}</option>
                                        @endforeach
                                    </select>
                                    <form action="" id="formSearch" method="post" style="width: 300px;">
                                        @csrf
                                        <div class="col-xl-2 col-lg-6 col-md-12 mb-1 d-flex form-search-name">
                                            <fieldset class="form-group" style="height: 30px;">
                                                <input type="text" class="form-control" id="search-video"
                                                    name="search-video" placeholder=""
                                                    style="box-shadow: 2px 3px 2px #e1e1e1; padding: 0 15px;">
                                                <button id="video_search" class="btn btn-search"
                                                    style="padding:0;box-shadow: 0px 3px 2px #e1e1e1;"><i
                                                        class="fa fa-search"></i></button>
                                            </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="questions_data">
                                @include('admin.video.grid')
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/video.js') }}' type="text/javascript"></script>
@endsection
