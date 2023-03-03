@extends('layouts.master')
@section('head')
    <link rel="stylesheet" type="text/css" href="/backend/app-assets/vendors/css/tables/datatable/datatables.min.css">
@endsection
@section('pageTitle', 'Questions')
@section('content')
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home"
                                    aria-hidden="true" style="font-size: 14px"></i></a> 〉<a href='#'>トレーニング管理　〉
                                テキスト登録</a></h5>
                        <div style="display:flex">
                            <h2 class="card-title" style="width:90%">テキスト登録</h2>
                            <a id="btn-mass-add" class="btn regist btn-mass-add" style="color:white">一括登録</a>
                            @include('components.modal-mass-import')
                        </div>
                    </div>
                    <div class="card-content">
                        {{-- <div class="registration-date">
                            登録日2022 / 10 / 22
                        </div> --}}
                        <div class="card-body">
                            <div class="card-form justify-content-between">
                                <div class="row">
                                    <form id="form-add-question" class="form-vr scroll_to"
                                        style="min-width:fit-content; width:100%" action="" method="GET">
                                        <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-add-vr">
                                            <fieldset class="form-group info-box-vr d-flex"
                                                style="margin-right: -19px;width: 1465px; height:490px">
                                                <div class="add-vr" style="width:645px">
                                                    <div class="d-flex title-2D" style="width:659px; margin-top:0">
                                                        <label for="" class="title-2D-label"
                                                            style="width: 82px;">問題</label>
                                                        <textarea name="title" id="title" class="input-question" style="margin-top:2%; height: 456px;"></textarea>
                                                    </div>
                                                    <p class="error-text" id="question-error-title"
                                                        style="margin-left: 116px; margin-top: 344px;">&nbsp;</p>
                                                </div>
                                                <div class="mass-registration" style="margin-top:-8px; margin-left:50px">
                                                    <div class="info-regist" style="width:300px">
                                                        <div class="publishing-settings"
                                                            style="margin-bottom: 30px;margin-top: -6px;">
                                                            <label class="question-option-label" for=""
                                                                style="letter-spacing: 0px;">4択回答</label>
                                                            <input type="checkbox" class="check-4-answer"
                                                                id="check-4-answer" value="" checked>
                                                            <input id="category_question" name="category_question"
                                                                value="2" hidden>
                                                        </div>
                                                        <div id="4_answers">
                                                            <div class="publishing-settings" style="margin-top:-8px">
                                                                <label class="question-option-label"
                                                                    for="">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                <div class="form-search-learning"
                                                                    style="display: -webkit-inline-box; width:400px;margin-left: 47px;">
                                                                    <div style="width:200px">
                                                                        <fieldset class="form-group form-answers"
                                                                            style="width:150px; margin-bottom:0">
                                                                            <a class="btn answer-label"
                                                                                style="color:white">1</a>
                                                                            <input type="text"
                                                                                class="form-control learning-inputs answers-input"
                                                                                style="width:165px" id="answer1"
                                                                                name="answer1" maxlength="255">
                                                                        </fieldset>
                                                                        <p class="error-text" id="question-error-answer1"
                                                                            style="position: absolute; margin:0%; margin-left: 56px;">
                                                                            &nbsp;</p>
                                                                    </div>
                                                                    <div style="width:200px">
                                                                        <fieldset class="form-group form-answers"
                                                                            style="width:150px; margin-bottom:0;margin-left: 41px;">
                                                                            <a class="btn answer-label"
                                                                                style="color:white">2</a>
                                                                            <input type="text"
                                                                                class="form-control learning-inputs answers-input"
                                                                                style="width:165px" id="answer2"
                                                                                name="answer2" maxlength="255">
                                                                        </fieldset>
                                                                        <p class="error-text" id="question-error-answer2"
                                                                            style="position: absolute; margin:0%; margin-left: 96px;">
                                                                            &nbsp;</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="publishing-settings" style="margin-top:32px">
                                                                <label class="question-option-label"
                                                                    for="">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                                <div class="form-search-learning"
                                                                    style="display: -webkit-inline-box; width:400px;margin-left: 47px;">
                                                                    <div style="width:200px">
                                                                        <fieldset class="form-group form-answers"
                                                                            style="width:150px; margin-bottom:0">
                                                                            <a class="btn answer-label"
                                                                                style="color:white">3</a>
                                                                            <input type="text"
                                                                                class="form-control learning-inputs answers-input"
                                                                                style="width:165px" id="answer3"
                                                                                name="answer3" maxlength="255">
                                                                        </fieldset>
                                                                        <p class="error-text" id="question-error-answer3"
                                                                            style="position: absolute; margin:0%; margin-left: 56px;">
                                                                            &nbsp;</p>
                                                                    </div>
                                                                    <div style="width:200px">
                                                                        <fieldset class="form-group form-answers"
                                                                            style="width:150px; margin-bottom:0;margin-left: 41px;">
                                                                            <a class="btn answer-label"
                                                                                style="color:white">4</a>
                                                                            <input type="text"
                                                                                class="form-control learning-inputs answers-input"
                                                                                style="width:165px" id="answer4"
                                                                                name="answer4" maxlength="255">
                                                                        </fieldset>
                                                                        <p class="error-text" id="question-error-answer4"
                                                                            style="position: absolute; margin:0%; margin-left: 96px;">
                                                                            &nbsp;</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="error-text question-answer-error">&nbsp;</p>
                                                            <div class="publishing-settings" style="margin-top: 68px;">
                                                                <label class="question-option-label" for=""
                                                                    style="letter-spacing: 16px;">正解</label>
                                                                <select name="correct_answer" id="correct_4_answer"
                                                                    style="width:202px" class="question-correct-answer">
                                                                    <option value="1" class="text-center">1</option>
                                                                    <option value="2" class="text-center">2</option>
                                                                    <option value="3" class="text-center">3</option>
                                                                    <option value="4" class="text-center">4</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="publishing-settings" id="correct_true_false"
                                                            style="margin-top: 43px; display:none">
                                                            <label class="question-option-label" for=""
                                                                style="letter-spacing: 16px;">正解</label>
                                                            <select name="correct_answer" id="correct_answer"
                                                                style="width:202px" class="question-correct-answer">
                                                                <option value="1" class="text-center">TRUE</option>
                                                                <option value="2" class="text-center">FALSE</option>
                                                            </select>
                                                        </div>
                                                        <div class="publishing-settings" style="margin-top: 30px;">
                                                            <label class="question-option-label"
                                                                for="">公開設定</label>
                                                            <select name="status" id="status" class="status-2D"
                                                                style="width:202px">
                                                                <option value="1" class="text-center">公開</option>
                                                                <option value="0" class="text-center">非公開
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="person-in-charge" style="margin-top: 30px;">
                                                            <label class="question-option-label"
                                                                for="">担当者名</label>
                                                            <input hidden class="question-add-account_id"
                                                                name="account_id" id="account_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <input readonly="readonly" class="question-add-account_id"
                                                                name="account_name" id="account_name"
                                                                value="{{ Auth::user()->full_name }}">
                                                        </div>
                                                        <div class="publishing-settings" style="margin-top: 30px;">
                                                            <label class="question-option-label"
                                                                style="letter-spacing: 17px;" for="">科目</label>
                                                            <select name="subject_id" id="subject_id"
                                                                class="question-level">
                                                                @foreach ($subject as $sub)
                                                                    <option value="{{ $sub->id }}"
                                                                        class="text-center">
                                                                        {{ $sub->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="publishing-settings" style="margin-top: 30px;">
                                                            <label class="question-option-label"
                                                                style="letter-spacing: 6px;">レベル</label>
                                                            <select name="question_level" id="question_level"
                                                                class="question-level">
                                                                @for ($i = 1; $i < 4; $i++)
                                                                    <option value="{{ $i }}"
                                                                        class="text-center">
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <input id="question_type" name="question_type" value="1"
                                                            hidden>
                                                    </div>
                                                    <div style="height: 295px;margin-top: 97px;margin-left: -6px;"
                                                        id="submit-cancel-buttons">
                                                        <a id="btn-cancel-question" class="btn regist"
                                                            style="background-color: #a1a1a1;margin-top: 179px;margin-left: 60px;width: 170px;height: 68px;padding-top: 23px; box-shadow: 6px 5px 13px #c1c1c1 inset;">キャンセル</a>
                                                        <a id="btn-store-question" class="btn regist"
                                                            style="margin-top: 48px;margin-left: 60px;width: 170px;height: 68px; padding-top: 23px;">追
                                                            加 登 録</a>
                                                        <a id="btn-update-question" data-id="" class="btn regist"
                                                            style="margin-top: 48px;margin-left: 60px;width: 170px;height: 68px; padding-top: 23px; display:none">アップデート</a>
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
                                @include('admin.question.text.grid')
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
    <script src='{{ asset('assets/js/admin/question.js') }}' type="text/javascript"></script>
    <script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
@endsection
