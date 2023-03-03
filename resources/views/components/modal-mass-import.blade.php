<div id="myModal" class="modal-import">
    <div class="modal-import-content">
        <div class="modal-import-header">
            <span class="modal-import-close">&times;</span>
            <h2 class="modal-import-title">質問のインポート</h2>
        </div>
        <div class="modal-import-body">
            {{-- <form id="mass-regist"> --}}
            <div class="modal-import-body2" style="padding-bottom: 1%">
                <input type="file" id="mass-import-media" name="file_medias" accept=".png,.jpeg,.jpg,.mp4" multiple
                    style="display:none;" />
                <button class="button-upload-mass-media" id="button-upload-mass-media"><img width=16
                        src="/backend/images/icons/upload.png">&emsp;複数のメディアをインポートする</button>
            </div>
            <div style="display:none;max-height:200px; width: 101.5%;" id="media_names" class="table-responsive">
            </div>
            <div class="modal-import-body2">
                <input type="file" id="mass-import" name="file_excel"
                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                    style="display:none;" />
                <button class="button-upload-mass" id="button-upload-mass"><img width=16
                        src="/backend/images/icons/upload.png">&emsp;アップロード</button>
                <p class="mass-import-name" id="mass-import-name"></p>
                <button class="button-upload-mass button-upload-ok" id="button-upload-ok" disabled>アップロード</button>
            </div>
            <a id="sample_question" class="pl-3" style="color: #009c9f; text-decoration: none;">(Sample Question) ユーザーの一括登録</a>
            <p>&emsp;</p>
            {{-- </form> --}}
        </div>
    </div>
</div>
