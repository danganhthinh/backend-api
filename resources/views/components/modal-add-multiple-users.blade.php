<div id="myModal" class="modal-import">

    <!-- Modal content -->
    <div class="modal-import-content">
        <div class="modal-import-header">
            <span class="modal-import-close">&times;</span>
            <h2 class="modal-import-title">ユーサーのインポート</h2>
        </div>
        <div class="modal-import-body">
            <form method="post" action="{{ route('user.import') }}" id="user-regist" enctype="multipart/form-data">
                @csrf
                <div class="modal-import-body2">
                    <input type="file" id="user-import" name="file_excel"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        style="display:none;" />
                    <button type="button" class="button-upload-user" id="button-upload-user"><img width=16
                            src="/backend/images/icons/upload.png">&emsp;ファイル選択</button>
                    <p class="user-import-name" id="user-import-name"></p>
                    <button type="button" class="button-upload-user button-upload-ok" id="button-upload-ok"
                        disabled>アップロード</button>
                </div>
                <p class="import-error text-danger pl-3 mb-2"></p>
                <a href="{{ url('/excel/(sample user) ユーザーの一括登録.xlsx') }}" class="pl-3">(Sample User) ユーザーの一括登録</a>
            </form>

            
        </div>
    </div>

</div>
