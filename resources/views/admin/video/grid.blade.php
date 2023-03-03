<div class="row form-row"  style="margin-left:-16px; margin-right: -26px;min-width: 100%; width:1552px">
    @if(@count($video))
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="table-layout: fixed">
            <thead class="table-head">
                <tr class="sticky-thead">
                    <th class="text-center" style="width: 5%;">
                        No
                        {{-- <span style="float: right;">
                            <i class="fa fa-sort-desc" aria-hidden="true"></i>
                        </span> --}}
                    </th>
                    <th class="text-center" style="width: 40%;">動画</th>
                    <th class="text-center" style="width: 20%;">科目</th>
                    <th class="text-center" style="width: 10%;">公開設定</th>
                    <th class="text-center" style="width: 22%;">担当者</th>
                    <th class="text-center" style="width: 10%;">登録日</th>
                    {{-- <th class="text-center" style="width: 10%;"></th> --}}
                    <th class="text-center" style="width: 10%;"></th>
                </tr>
            </thead>
            <tbody class="table-body table-videos">
                @foreach ($video as $vid)
                    @if (isset($vid->id))
                        <tr style="height: 35px">
                            <td class="text-center">
                                {{ ($video->currentPage() - 1) * $video->perPage() + $loop->index + 1 }}
                            </td>
                            <td style="text-align:left; padding-left:4%" class="question-title-ellipsis">
                                {{ $vid->title }}</td>
                            <td style="text-align:left; padding-left:4%" class="question-title-ellipsis">
                                    {{ $vid->subject_name }}</td>
                            <td>
                                @if ($vid->status == 1)
                                    公開
                                @else
                                    非公開
                                @endif
                            </td>
                            <td>
                                @if ($vid->user_name)
                                    {{ $vid->user_name }}
                                @endif
                            </td>
                            <td>{{ $vid->created_at->format('Y/m/d') }}</td>
                            {{-- <td></td> --}}
                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                <td>
                                    <a class="warning mr-1" data-id="{{ $vid->id }}" id="btn-edit-video"><img
                                            width=16 src="/backend/images/icons/edit.png"></a>
                                    <button class="danger delete" data-id="{{ $vid->id }}"
                                        id="btn-delete-video"><img width=15 style="padding-bottom: 2%;"
                                            src="/backend/images/icons/delete.png"></button>
                                </td>
                            @endif
                            {{-- preparing datas for editting --}}
                            <input data-id="{{ $vid->id }}" name="title" value="{{ $vid->title }}" hidden>
                            <input data-id="{{ $vid->id }}" name="video_level" value="{{ $vid->video_level }}"
                                hidden>
                            <input data-id="{{ $vid->id }}" name="account_name"
                                @if ($vid->user_name) value="{{ $vid->user_name }}" @endif hidden>
                            <input data-id="{{ $vid->id }}" name="account_id"
                                @if ($vid->account_id) value="{{ $vid->account_id }}" @endif hidden>
                            <input data-id="{{ $vid->id }}" name="subject_id" value="{{ $vid->subject_id }}"
                                hidden>
                            <input data-id="{{ $vid->id }}" name="status" value="{{ $vid->status }}" hidden>
                            <input data-id="{{ $vid->id }}" name="media"
                                value="{{ basename($vid->file_path) }}" hidden>
                            <input data-id="{{ $vid->id }}" name="thumbnail"
                                value="{{ basename($vid->thumbnail) }}" hidden>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="text-center col-12 font-weight-bold">
            <h2 class="text-light">データがありません。</h2>
        </div>
    @endif
    <div id="pagination" style="margin-top:3%">
        {{ $video->links('pagination::bootstrap-4') }}
    </div>
</div>
