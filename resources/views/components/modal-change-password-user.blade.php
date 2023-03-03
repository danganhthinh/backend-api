<div id="modalChangePassword" class="modal-import">

    <!-- Modal content -->
    <div class="modal-import-content w-50">
        <div class="modal-import-header">
            <span class="modal-import-close">&times;</span>
            <h2 class="modal-import-title"> パスワード変更</h2>
        </div>
        <div class="modal-import-body p-0">
            <form action="{{ url('/admin/user/change-student-password/'.$user->id) }}" method="post" id="form-change-password-user" data-id="{{ $user->id }}">
                @csrf
                <div class="col-xl-2 col-lg-6 col-md-12 mb-1 form-regist rounded-0">
                    <fieldset class="form-group">
                        <div class="box d-flex">
                            <label for="" class="mt-le-1">元のパスワード</label>
                            <div class="d-block">
                                <input type="password" name="current_password" class="password" placeholder=""
                                    id="current_password" value="" data-toggle="password"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                    maxlength="12">
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye-slash field_icon toggle-password-current-password"></span>

                                <p class="text-danger error-text current_password_error"></p>
                            </div>
                        </div>
                        <div class="box d-flex">
                            <label for="" class="mt-le-1">新規パスワード</label>
                            <div class="d-block">
                                <input type="password" name="password" class="password" placeholder="" id="password"
                                    value=""
                                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                    maxlength="12">
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye-slash field_icon toggle-password"></span>
                                <p class="text-danger error-text password_error"></p>
                            </div>
                        </div>
                        <div class="box">
                            <div class="d-block position-relative">
                                <label for=""
                                    style="height: 80px; vertical-align: top">新規パスワード&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    （確認用）</label>
                                <div class="d-inline-block">
                                    <input type="password" name="confirm_password" class="password" placeholder=""
                                        id="confirm_password" value="" style="height: 40px; margin-left: 16px"
                                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')"
                                        maxlength="12">
                                    <span toggle="#password-field"
                                        class="fa fa-fw fa-eye-slash field_icon toggle-password-confirm-password"
                                        style=""></span>

                                    <p class="text-danger error-text confirm_password_error" style=""></p>
                                </div>
                                <button type="button" class="float-right btn btn-change-password"
                                    id="change-password-user">追&nbsp;&nbsp;&nbsp;&nbsp;加&nbsp;&nbsp;&nbsp;&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;録
                                </button>
                            </div>


                        </div>

                    </fieldset>
                </div>
            </form>

            {{-- <a href="{{ url('/storage/excel/Error_') }}" class="text-danger error-text"></a> --}}
        </div>
    </div>

</div>
