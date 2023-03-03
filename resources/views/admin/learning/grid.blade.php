<div class="row form-row">
    @if(@count($learning))
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-head">
                <tr class="sticky-thead">
                    <th style="width:0.50%"></th>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th class="text-center" style="width: 20%;">氏名</th>
                    <th class="text-center" style="width: 15%;">13要素</th>
                    <th class="text-center" style="width: 9%;">レベル</th>
                    <th class="text-center" style="width: 9%;">トレーニング実施回数</th>
                    <th class="text-center" style="width: 9%;">トレーニング正解回数</th>
                    <th class="text-center" style="width: 12%;"> 動画学習実施回数</th>
                    {{-- <th class="text-center" style="width: 10%;"> 動画トレーニング正解率</th> --}}
                    <th class="text-center" style="width: 4%;"></th>
                    <th class="text-center" style="width: 4%;"></th>
                </tr>
            </thead>
            @if (isset($learning))
                @foreach ($learning as $learn)
                    <tbody class="table-body learning-data">
                        <tr @if ($loop->index % 2 == 0) style="background-color: white" @endif>
                            <td class="arrow learning-arrow" id="learning-arrow"
                                data-id={{ $learn['account_id'] }} first_click='1'>
                                &blacktriangleright;</td>
                            <td style="padding-left: 2%; text-align:left; padding-right:0">
                                {{ ($learning->currentPage() - 1) * $learning->perPage() + $loop->index + 1 }}
                            </td>
                            <td class="learning-names" style="padding-right: 0;float: left;padding-top: 3%;padding-left: 25%;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 80%;" id="student_name" data-id={{ $learn['account_id'] }}>
                                {{ $learn['account_name'] }}</td>
                            <td class="learning-names" style="padding-right: 0;padding-left: 5%;" >{{ $learn['subject_name'] }}</td>
                            <td>{{ $learn['level'] }} 級</td>
                            <td>{{ $learn['number_training'] }} 回</td>
                            <td>{{ $learn['number_correct_answers'] }} 回</td>
                            <td>{{ $learn['video_number_learning'] }} 回</td>
                            {{-- <td>{{ $learn['correct_answer_video'] }} 回</td> --}}
                            <td style="padding:0; background-color:#fafafa"><button class="button-learning-detail"
                                    data-name={{ $learn['account_name'] }}
                                    data-id={{ $learn['account_id'] }}>詳細</button>
                            </td>
                            <td style="padding:0; background-color:#fafafa"><button class="button-learning-compare"
                                data-name={{ $learn['account_name'] }}
                                data-id={{ $learn['account_id'] }}>比較</button>
                        </td>
                        </tr>
                    <tbody class="table-body learning-expands hidden"
                        data-id={{ $learn['account_id'] }}
                        @if ($loop->index % 2 == 0) style="background-color: white" @else  style="background-color: #fafafa" @endif>
                        <tr>
                            <td
                                @if ($loop->index % 2 == 0) style="background-color: white" @else  style="background-color: #fafafa" @endif>
                            </td>
                            <td
                                @if ($loop->index % 2 == 0) style="background-color: white" @else  style="background-color: #fafafa" @endif>
                            </td>
                            <td
                                @if ($loop->index % 2 == 0) style="background-color: white" @else  style="background-color: #fafafa" @endif>
                            </td>
                            <td class="learning-expands-td td-subject-name" style="padding-left: 5%;" data-id={{ $learn['account_id'] }}
                                data-name="subject_name">
                            </td>
                            <td class="learning-expands-td" data-id={{ $learn['account_id'] }}
                                data-name="level">
                            </td>
                            <td class="learning-expands-td" data-id={{ $learn['account_id'] }}
                                data-name="number_training"></td>
                            <td class="learning-expands-td" data-id={{ $learn['account_id'] }}
                                data-name="number_correct_answers">
                            </td>
                            <td class="learning-expands-td" data-id={{ $learn['account_id'] }}
                                data-name="video_number_learning">
                            </td>
                            {{-- <td class="learning-expands-td" data-id={{ $learn['account_id'] }}
                                data-name="correct_answer_video">
                            </td> --}}
                            <td style="background-color: #fafafa"></td>
                        </tr>
                    </tbody>
                    </tbody>
                @endforeach
            @endif
        </table>
    </div>
    <div id="learning_pagination" style="margin-top:3%">
        {{ $learning->links('pagination::bootstrap-4') }}
    </div>
    @else
        <div class="text-center col-12 font-weight-bold">
            <h2 class="text-light">データがありません。</h2>
        </div>
    @endif
</div>
