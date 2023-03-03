@extends('backend.layouts.master')
@section('content')
    <!-- Breadcrumb -->
    <div class="content-header-left col-md-6 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/backend/dashboard">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{route('nk-categories.index')}}">Danh mục</a>
                    </li>
                    <li class="breadcrumb-item active">{{@$category ? 'Cập nhật danh mục' : 'Thêm mới danh mục'}}
                    </li>
                </ol>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="basic-form-layouts">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header p-0">
                                <h4 class="card-title">Thông tin danh mục</h4>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <form class="form-horizontal" method="POST" action="{{ $action }}"
                                      enctype="multipart/form-data">
                                    @if (isset($category))
                                        {{ method_field('PUT') }}
                                    @endif
                                    @csrf
                                    <input type="hidden" name="id" value="{{@$category->id}}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div
                                                class="form-group required {{ $errors->has('name') ? 'has-error' : '' }}">
                                                <label for="name">Tên danh mục <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" placeholder="" name="name"
                                                       value="{{ @$category->name  ? : old('name') }}">
                                                <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            {{--                                            <div class="form-group required {{ $errors->has('name') ? 'has-error' : '' }}">--}}
                                            {{--                                                <label for="name">Danh mục cha<span style="color:red">*</span></label>--}}
                                            {{--                                                <input type="text" class="form-control" placeholder="" name="name"--}}
                                            {{--                                                       value="{{ @$category->name  ? : old('name') }}">--}}
                                            {{--                                                <span class="help-block">{{ $errors->first('name', ':message') }}</span>--}}
                                            {{--                                            </div>--}}
                                            <fieldset class="form-group">
                                                <label for="name">Chọn danh mục cha</label>
                                                <select type="text" class="form-control"
                                                        name="parent_id"
                                                        id="type">
                                                    <option
                                                        value="{{\App\Constants\NkCategoriesSetting::PARENT_ID}}" {{ \App\Constants\NkCategoriesSetting::PARENT_ID == @$category->parent_id ? 'selected':''}}>Chọn Danh Mục
                                                    </option>
                                                    @foreach($categories as $key => $value)
                                                        <option
                                                            value="{{$value->id}}" {{ $value->id == @$category->parent_id ? 'selected':''}}>{{$value->name}}</option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-6">
                                            <div
                                                class="form-group required {{ $errors->has('iconFile') ? 'has-error' : '' }}">
                                                <label for="name">Hình ảnh <span style="color:red">*</span></label>
                                                <div class="custom-file mb-4">
                                                    <input type="file" class="custom-file-input" id="iconFile"
                                                           name="iconFile"
                                                           accept="image/*">
                                                    <label class="custom-file-label" for="iconFile">Chọn Ảnh</label>
                                                    <span
                                                        class="help-block">{{ $errors->first('iconFile', ':message') }}</span>
                                                    @if (!empty($category->image))
                                                        <input type="hidden" name="image"
                                                               value="{{ $category->image }}">
                                                    @endif
                                                </div>
                                                <br>
                                                <div class="image-preview" style="width:50%; margin: auto;">
                                                    <img style="width:100%;height:auto;" id="preview"
                                                         src="{{ isset($category) && isset($category->image) ? $category->image : '#' }}"
                                                         alt=""/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <a href="{{route('nk-categories.index')}}" class="btn btn-warning mr-1">
                                            <i class="ft-x"></i> Hủy
                                        </a>
                                        @if((isset($category) && Auth::user()->checkPermissionSuperAdmin(\App\Constants\CmsActionsSetting::SETTING_CATEGORY_NK_PRODUCT_EDIT)) 
                                        || (!isset($category) && Auth::user()->checkPermissionSuperAdmin(\App\Constants\CmsActionsSetting::SETTING_CATEGORY_NK_PRODUCT_CREATE)))
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-check-square-o"></i> Lưu
                                        </button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $("#iconFile").change(function () {
                previewImage(this, '#preview');
            });
        });
    </script>
@endsection
