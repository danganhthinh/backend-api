<div class="row form-row"  style="margin-left:-16px; margin-right: -26px;">
    <div class="avatar-parent-div">
        @foreach ($avatar->reverse() as $avatar)
            <table class="table table-bordered learning-analysis-table-chart avatar-table">
                <thead>
                    <tr>
                        <th class="avatar-title" style="border:none">
                            @if ($avatar->type == '1')
                                GOOD用
                            @elseif ($avatar->type == '2')
                                EXCELLENT用
                            @endif
                        </th>
                    </tr>
                </thead>
                <tbody class="table-body table-avatar-body">
                    <tr>
                        <td>
                            <label class="upload-avatar-label">アバター</label>
                            <form id="form-avatar-{{ $avatar->id }}" enctype="multipart/form-data">
                                <input accept="image/*" type="file" class="button-upload-avatar" name="avatar"
                                    id="button-upload-avatar-{{ $avatar->id }}" data-id="{{ $avatar->id }}">
                                <label class="avatar-buttons" for="button-upload-avatar-{{ $avatar->id }}">ファイルを選択</label>
                                <input name="type" value="1" hidden>
                                <input name="status" value="1" hidden>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span id="file-{{ $avatar->id }}-name"
                                style="font-size:16px;display: inline-block;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 375px;height:19px">{{ basename($avatar->image) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img id="{{ $avatar->id }}_preview"
                                src="{{ asset('storage/' . basename($avatar->image)) }}" class="avatar-preview fullable"
                                width="200px" height="180px">
                        </td>
                    </tr>
                    <tr>
                        <td class="avatar-table-bottom">
                            <div id="avatar-cancel-save" class="avatar-cancel-save" style="margin-left: -8%; visibility:hidden"  data-id="{{ $avatar->id }}">
                            <button class="button-cancel-avatar" id="button-cancel-avatar-{{ $avatar->id }}" data-id="{{ $avatar->id }}">キャンセル</button>
                            <button class="button-save-avatar" id="button-save-avatar-{{ $avatar->id }}"
                                data-id="{{ $avatar->id }}" data-type="{{ $avatar->type }}">登録する</button>
                            </div>
                        </td>
                    </tr>
                    <input hidden class="avatar-image-name" data-id="{{ $avatar->id }}" value={{ basename($avatar->image) }} >
                </tbody>
            </table>
        @endforeach
    </div>
</div>
