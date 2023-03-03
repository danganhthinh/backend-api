<div class="row form-row"  style="margin-left:-16px; margin-right: -26px;min-width: 100%; width:1552px">
    @if(@count($question))
    <div class="table-responsive">
        <table class="table table-bordered table-striped" style="table-layout: fixed">
            <thead class="table-head">
                <tr class="sticky-thead">
                    <th class="text-center" style="width: 4%;">
                        No
                        {{-- <span style="float: right;">
                            <i class="fa fa-sort-desc" aria-hidden="true"></i>
                        </span> --}}
                    </th>
                    {{-- phần bảng này đợi khách design lại  --}}
                    <th class="text-center" style="width: 20%;">問題</th>
                    <th class="text-center" style="width: 10%;">科目</th>
                    <th class="text-center" style="width: 8%;">公開設定</th>
                    <th class="text-center" style="width: 10%;">担当者</th>
                    <th class="text-center" style="width: 10%;">登録日</th>
                    <th class="text-center" style="width: 8%;"></th>
                </tr>
            </thead>
            <tbody class="table-body table-videos">
                @foreach ($question as $ques)
                    @if (isset($ques->id))
                        <tr style="height: 35px">
                            <td class="text-center">
                                {{ ($question->currentPage() - 1) * $question->perPage() + $loop->index + 1 }}
                            </td>
                            <td style="text-align:left; padding-left:4%" class="question-title-ellipsis">
                                {{ str_replace('<br />', "", $ques->title); }}</td>
                                <td>
                                    @if ($ques->subject_name)
                                        {{ $ques->subject_name }}
                                    @endif
                                </td>
                                <td>
                                @if ($ques->status == 1)
                                    公開
                                @else
                                    非公開
                                @endif
                            </td>
                            <td>
                                @if ($ques->mentor)
                                    {{ $ques->mentor->full_name }}
                                @endif
                            </td>
                            <td>{{ $ques->created_at->format('Y/m/d') }}</td>
                            @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                <td>
                                    <a class="warning mr-1" data-id="{{ $ques->id }}" id="btn-edit-question"><img
                                            width=16 src="/backend/images/icons/edit.png"></a>
                                    {{-- <a class="danger delete" data-id="{{ $ques->id }}" id="btn-delete-question"><img
                                        width=15 style="padding-bottom: 2%;" src="/backend/images/icons/delete.png"></a> --}}
                                </td>
                            @endif
                        </tr>
                        <textarea data-id="{{ $ques->id }}" name="title" hidden>{{ $ques->title }}</textarea>
                        <input data-id="{{ $ques->id }}" name="answer1" value="{{ $ques->answer1 }}" hidden>
                        <input data-id="{{ $ques->id }}" name="answer2" value="{{ $ques->answer2 }}" hidden>
                        <input data-id="{{ $ques->id }}" name="answer3" value="{{ $ques->answer3 }}" hidden>
                        <input data-id="{{ $ques->id }}" name="answer4" value="{{ $ques->answer4 }}" hidden>
                        <input data-id="{{ $ques->id }}" name="category_question"
                            value="{{ $ques->category_question }}" hidden>
                        <input data-id="{{ $ques->id }}" name="correct_answer" value="{{ $ques->correct_answer }}"
                            hidden>
                        <input data-id="{{ $ques->id }}" name="question_level" value="{{ $ques->question_level }}"
                            hidden>
                        <input data-id="{{ $ques->id }}" name="account_name"
                            @if ($ques->mentor) value="{{ $ques->mentor->full_name }}" @endif hidden>
                        <input data-id="{{ $ques->id }}" name="account_id"
                            @if ($ques->mentor) value="{{ $ques->mentor->id }}" @endif hidden>
                        <input data-id="{{ $ques->id }}" name="subject_id" value="{{ $ques->subject_id }}" hidden>
                        <input data-id="{{ $ques->id }}" name="status" value="{{ $ques->status }}" hidden>
                        <input data-id="{{ $ques->id }}" name="media" value="{{ basename($ques->media) }}"
                            hidden>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div id="pagination" style="margin-top:3%" @if($question->total()==0) hidden @endif>
            {{ $question->links('pagination::bootstrap-4') }}
        </div>
    </div>
    @else
        <div class="text-center col-12 font-weight-bold">
            <h2 class="text-light">データがありません。</h2>
        </div>
    @endif
</div>
