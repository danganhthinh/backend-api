@extends('layouts.master')
@section('content')
@section('pageTitle', 'Notification')
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-position"><a href="{{ url('admin/learning') }}"><i class="fa fa-home" aria-hidden="true"
                                style="font-size: 14px"></i></a> 〉 <a href="#" class="text-dark">プッシュ通知</a></h5>
                    <h2 class="card-title">プッシュ通知</h2>

                </div>
                <div class="card-content mt-4">
                    <div class="card-body">
                        <form id="formSendNotification" action="{{ route('notification') }}" method="POST">
                            @csrf
                            <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-regist">
                                <fieldset class="form-group ">
                                    <div class="box d-flex">
                                        {{-- check again  --}}

                                        <div class="d-flex list-school">
                                            <label for="" class="mt-le-1">学校名</label>
                                            <div class="d-block">
                                                @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                                    <select name="" id="school"
                                                        class="school-name span-ellipsis" required>
                                                        <option value="">全て</option>
                                                        <optgroup label="School">
                                                            @foreach ($school as $item_school)
                                                                <option data-type="school"
                                                                    value="{{ $item_school->id }}"
                                                                    data-school-id="{{ $item_school->id }}"
                                                                    data-school-code="{{ $item_school->code }}"
                                                                    data-school-name="{{ $item_school->name }}">
                                                                    {{ Str::limit($item_school->name, 30) }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                        <optgroup label="Group">
                                                            @foreach ($group as $item_group)
                                                                <option data-type="group" value="{{ $item_group->id }}"
                                                                    data-group-id="{{ $item_group->id }}"
                                                                    data-group-code="{{ $item_group->code }}"
                                                                    data-group-name="{{ $item_group->name }}">
                                                                    {{ Str::limit($item_group->name, 30) }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                    <p class="text-danger error-text school_error"></p>
                                                @endif
                                                @if (in_array(Auth::user()->role_id, [\App\Consts::MENTOR]))
                                                    {{-- <?php dd($school); ?> --}}
                                                    @if ($school->isNotEmpty() && $group->isEmpty())
                                                        <input type="" name="" id="" readonly
                                                            class="bg-transparent"
                                                            value="@foreach ($school as $item_school) {{ $item_school->name }} @endforeach">
                                                    @endif

                                                    @if ($school->isEmpty() && $group->isNotEmpty())
                                                        <input type="" name="" id="" readonly
                                                            class="bg-transparent"
                                                            value="@foreach ($group as $item_group) {{ $item_group->name }} @endforeach">
                                                    @endif
                                                    @if ($school->isEmpty() && $group->isEmpty())
                                                        <input type="" name="" id="" readonly
                                                            class="bg-transparent text-danger" value="どの学校/団体にまだ所属しない教師">
                                                    @endif
                                                    {{-- <select name="" id="school"
                                                        class="school-name span-ellipsis" required>
                                                        @foreach ($school as $item_school)
                                                            <option data-type="school" value="{{ $item_school->id }}"
                                                                data-school-id="{{ $item_school->id }}"
                                                                data-school-code="{{ $item_school->code }}"
                                                                data-school-name="{{ $item_school->name }}">
                                                                {{ Str::limit($item_school->name, 30) }}
                                                            </option>
                                                        @endforeach
                                                        @foreach ($group as $item_group)
                                                            <option data-type="group" value="{{ $item_group->id }}"
                                                                data-group-id="{{ $item_group->id }}"
                                                                data-group-code="{{ $item_group->code }}"
                                                                data-group-name="{{ $item_group->name }}">
                                                                {{ Str::limit($item_group->name, 30) }}
                                                            </option>
                                                        @endforeach
                                                    </select> --}}
                                                @endif

                                            </div>
                                        </div>

                                    </div>
                                    <div class="box d-flex">
                                        <div class="d-flex">
                                            <label for="" class="mt-le-1">所属</label>
                                            <div class="d-block">
                                                <select name="" id="grade" disabled
                                                    class="bg-transparent span-ellipsis" style="background-image: none">
                                                </select>
                                                <p class="text-danger error-text grade_error"></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box d-flex">
                                        <label for="" class="mt-le-1">タイトル</label>
                                        <div class="d-block">
                                            <input type="text" class="" name="title" placeholder="タイトル"
                                                id="title" maxlength="100">
                                            <p class="text-danger error-text title_error"></p>
                                        </div>
                                    </div>
                                    <div class="box d-flex justify-content-between">
                                        <div class="d-block position-relative">
                                            {{-- <div class="d-flex"> --}}
                                            <label for="" class="position-absolute label-title">メッセージ</label>
                                            {{-- </div> --}}
                                            <textarea name="message" placeholder="メッセージ" class="message" id="message" maxlength="300"></textarea>
                                            <p class="text-danger error-text message_error" style="padding-left: 11rem">
                                            </p>

                                        </div>
                                        <button type="button" class="btn btn-send-noti"
                                            id="push-noti">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<link rel="stylesheet" type="text/css" href="/backend/assets/css/notification.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src='{{ asset('assets/js/admin/functions/toast.js') }}' type="text/javascript"></script>
<script src="/backend/assets/js/notification.js"></script>
@endsection
