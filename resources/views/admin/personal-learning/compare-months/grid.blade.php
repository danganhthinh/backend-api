<div class="row form-row"
    style="margin-left:2px; margin-right: -26px;min-width: 100%; width:1500px">
    <div class="table-responsive" id="compare-learning-to_print" >
        <table class="table table-bordered">
            <thead class="table-head sticky-thead">
                <tr>
                    <th class="personal-learning-analysis-title" style="border:none" colspan=7>生徒氏名
                        <b>{{ $full_name }}</b>
                    </th>
                </tr>
            </thead>
            <tbody class="table-body table-body-learning table-compare" style="background-color: white;">
                <tr class="compare-name" style="height: 65px">
                    <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                    <td class="td-grid2" style="width: 2%;"></td>
                    <td class="td-grid2" style="width: 20%;">期間</td>
                    <td class="td-grid2" style="width: 25%;font-weight:600"><input class="compare-learning-select-month"
                            id="learning-select-month1"
                            value="{{ substr($data_comparison_month['month1'], -2) . '年' . substr($data_comparison_month['month1'], 0, 2) . '月' }}">
                    </td>
                    <td class="td-grid2" style="width: 27%;line-height:0;">
                        <span class="noselect" style="color:black">&#10095;</span>
                    </td>
                    <td class="td-grid2" style="width: 23%;font-weight:600"><input class="compare-learning-select-month"
                            id="learning-select-month2"
                            value="{{ substr($data_comparison_month['month2'], -2) . '年' . substr($data_comparison_month['month2'], 0, 2) . '月' }}">
                    </td>
                    <td></td>
                </tr>
                <tr style="background-color: #EBEBEB">
                    <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                    <td class="td-grid2" style="width: 2%;">
                        @if($level_total)
                        @if ( $level_total['month2'] == 1)
                            <span style="color:red; padding-left: 50%;" class="fa fa-star"></span>
                        @endif
                        @endif
                    </td>
                    <td class="td-grid2" style="width: 20%;">全体レベル</td>
                    <td class="td-grid2" style="width: 25%;padding-left: 5%;">
                        @if ($level_total)
                            {{ $level_total['month1'] }} 級
                        @else
                            0 級
                        @endif
                    </td>
                    <td class="td-grid2" style="width: 27%;line-height:0;">
                        <span class="noselect">&#10095;</span>
                    </td>
                    <td class="td-grid2" style="width: 23%;padding-left: 5%;">
                        @if ($level_total)
                            {{ $level_total['month2'] }} 級
                        @else
                            0 級
                        @endif
                    </td>
                    <td class="td-grid2"
                        style="vertical-align: middle; width: 1%;line-height:0;background-color: white;padding-left: 0;font-size: 40px;height:29px">
                        <span style="color:#EBEBEB;" class="tr-end">&#129170;</span>
                    </td>
                </tr>
                @foreach ($data['month1'] as $month1)
                    @foreach ($data['month2'] as $month2)
                        @if ($month1['subject_name'] == $month2['subject_name'])
                            <tr
                                @if ($loop->index % 2 != 0) style="background-color: #EBEBEB;" @elseif($loop->index % 2 == 0) style="background-color: white" @endif>
                                <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                                <td class="td-grid2" style="width: 2%;">
                                    @if ($month2['level'] == 1)
                                        <span style="color:red; padding-left: 50%;" class="fa fa-star"></span>
                                    @endif
                                </td>
                                <td class="td-grid2" style="width: 20%;">{{ $month1['subject_name'] }}</td>
                                <td class="td-grid2" style="width: 25%;padding-left: 5%;">{{ $month1['level'] }} 級</td>
                                <td class="td-grid2" style="width: 27%;line-height:0;">
                                    @if ($loop->index % 2 != 0)
                                        <span class="noselect">&#10095;</span>
                                    @endif
                                </td>
                                <td class="td-grid2" style="width: 23%;padding-left: 5%;">{{ $month2['level'] }} 級</td>
                                <td class="td-grid2"
                                    style="vertical-align: middle; width: 1%;line-height:0;background-color: white;padding-left: 0;font-size: 40px;height:29px">
                                    @if ($loop->index % 2 != 0)
                                        <span style="color:#EBEBEB;" class="tr-end">&#129170;</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                {{-- number_training --}}
                @foreach ($data['month1'] as $month1)
                    @foreach ($data['month2'] as $month2)
                        @if ($month1['account_id'] == $month2['account_id'])
                            <tr style="background-color: #EBEBEB;">
                                <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                                <td class="td-grid2" style="width: 2%;">
                                </td>
                                <td class="td-grid2" style="width: 20%;">トレーニング実施回数</td>
                                <td class="td-grid2" style="width: 25%;padding-left: 5%;">
                                    <a style="cursor: default;" id="training1">{{$sum_number_training['month1']}}</a> 回
                                </td>
                                <td class="td-grid2" style="width: 27%;line-height:0;"><span
                                        class="noselect">&#10095;</span></td>
                                <td class="td-grid2" style="width: 23%;padding-left: 5%;">
                                    <a style="cursor: default;" id="training2">{{$sum_number_training['month2']}}</a> 回
                                </td>
                                <td class="td-grid2"
                                    style="vertical-align: middle; width: 1%;line-height:0;background-color: white;padding-left: 0;font-size: 40px;height:29px">
                                    <span style="color:#EBEBEB;" class="tr-end">&#129170;</span>
                                </td>
                            </tr>
                        @endif
                        <?php break; ?>
                    @endforeach
                    <?php break; ?>
                @endforeach
                {{-- number_correct_answers --}}
                @foreach ($data['month1'] as $month1)
                    @foreach ($data['month2'] as $month2)
                        @if ($month1['account_id'] == $month2['account_id'])
                            <tr style="background-color: white">
                                <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                                <td class="td-grid2" style="width: 2%;">
                                </td>
                                <td class="td-grid2" style="width: 20%;">トレーニング正解回数</td>
                                <td class="td-grid2" style="width: 25%;padding-left: 5%;">
                                    <a style="cursor: default;"
                                        id="training_correct1">{{$sum_number_correct_answers['month1']}}</a> 回
                                </td>
                                <td class="td-grid2" style="width: 27%;line-height:30px;">&nbsp;</td>
                                <td class="td-grid2" style="width: 23%;padding-left: 5%;">
                                    <a style="cursor: default;"
                                        id="training_correct2">{{$sum_number_correct_answers['month2']}}</a> 回
                                </td>
                                <td class="td-grid2">
                                </td>
                            </tr>
                        @endif
                        <?php break; ?>
                    @endforeach
                    <?php break; ?>
                @endforeach
                {{-- training_correct_rate --}}
                @foreach ($data['month1'] as $month1)
                    @foreach ($data['month2'] as $month2)
                        @if ($month1['account_id'] == $month2['account_id'])
                            <tr style="background-color: #EBEBEB;">
                                <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                                <td class="td-grid2" style="width: 2%;">
                                </td>
                                <td class="td-grid2" style="width: 20%;">正解率</td>
                                <td class="td-grid2" style="width: 25%;padding-left: 5%;">
                                    <a style="cursor: default;"
                                        id="training_correct_rate1">{{round($sum_training_correct_rate['month1'],4)*100}}</a> %
                                </td>
                                <td class="td-grid2" style="width: 27%;line-height:0;"><span
                                        class="noselect">&#10095;</span></td>
                                <td class="td-grid2" style="width: 23%;padding-left: 5%;">
                                    <a style="cursor: default;"
                                        id="training_correct_rate2">{{round($sum_training_correct_rate['month2'],4)*100}}</a> %
                                </td>
                                <td class="td-grid2"
                                    style="vertical-align: middle; width: 1%;line-height:0;background-color: white;padding-left: 0;font-size: 40px;height:29px">
                                    <span style="color:#EBEBEB;" class="tr-end">&#129170;</span>
                                </td>
                            </tr>
                        @endif
                        <?php break; ?>
                    @endforeach
                    <?php break; ?>
                @endforeach
                {{-- video_number_learning --}}
                @foreach ($data['month1'] as $month1)
                    @foreach ($data['month2'] as $month2)
                        @if ($month1['account_id'] == $month2['account_id'])
                            <tr style="background-color: white">
                                <td class="td-grid2" style="width: 1%;background-color: white">&nbsp;</td>
                                <td class="td-grid2" style="width: 2%;">
                                </td>
                                <td class="td-grid2" style="width: 20%;">動画視聴回数</td>
                                <td class="td-grid2" style="width: 25%;padding-left: 5%;">
                                    {{$sum_video_number_learning['month1']}} 回</td>
                                <td class="td-grid2" style="width: 27%;line-height:30px;">&nbsp;</td>
                                <td class="td-grid2" style="width: 23%;padding-left: 5%;">
                                    {{$sum_video_number_learning['month2']}} 回</td>
                                <td class="td-grid2">
                                </td>
                            </tr>
                            <input value="{{ $month1['account_id'] }}" id="accound-id-compare" hidden>
                            <input value="{{ $month1['year'] }}" id="year-compare" hidden>
                        @endif
                        <?php break; ?>
                    @endforeach
                    <?php break; ?>
                @endforeach
            </tbody>
        </table>
    </div>
    <input id="sum_number_training" value={{$sum_number_training['month1']}} hidden>
    <input id="sum_number_training2" value={{$sum_number_training['month2']}} hidden>
    <input id="sum_number_correct_answers" value={{$sum_number_correct_answers['month1']}} hidden>
    <input id="sum_number_correct_answers2" value={{$sum_number_correct_answers['month2']}} hidden>
    <input id="sum_training_correct_rate" value={{round($sum_training_correct_rate['month1'],4)*100}} hidden>
    <input id="sum_training_correct_rate2" value={{round($sum_training_correct_rate['month2'],4)*100}} hidden>
    <table class="table table-bordered learning-analysis-table-chart page-break" id="chart1"
        style="border-radius: 20px; width:fit-content; height:50%; margin:auto;margin-top:5%; box-shadow: 1px 3px 8px #d8d8d8;">
        <thead class="table-head">
            <tr>
                <th class="compare-chart" style="border:none">&nbsp;</th>
            </tr>
        </thead>
        <tbody class="table-body table-body-learning" style="background-color:#F7F7F7">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="float:right; font-weight:600; padding-right: 8%;"><span
                        style='font-size:18px;color:#7ee48b'>&#9632;</span><span class="learning-month1">００年００月</span>
                </td>
            </tr>
            <tr>
                <td style="float:right; font-weight:600; padding-right: 8%;"><span
                        style='font-size:18px;color:#707070'>&#9632;</span><span class="learning-month2">００年００月
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <canvas id="compare-training-chart" width="1355" height="300"
                        style="padding-top:1%; padding-bottom:2%"></canvas>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="padding-right: 39%; font-weight: 800;">トレーニング実施回数</span>
                    <span style="padding-right: 0%; font-weight: 800;">トレーニング正解回数</span>
                </td>
            </tr>
            <tr>
                <td> &nbsp;</td>
            </tr>
            <tr>
                <td> &nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered learning-analysis-table-chart" id="chart2"
        style="border-radius: 20px; width:fit-content; height:50%; margin:auto;margin-top:5%; box-shadow: 1px 3px 8px #d8d8d8;">
        <thead class="table-head">
            <tr>
                <th class="compare-chart" style="border:none; background-color:#FCAE71">&nbsp;</th>
            </tr>
        </thead>
        <tbody class="table-body  table-body-learning" style="background-color:#F7F7F7">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="float:right; font-weight:600; padding-right: 8%;"><span
                        style='font-size:18px;color:#FCAE71'>&#9632;</span><span class="learning-month1">００年００月</span>
                </td>
            </tr>
            <tr>
                <td style="float:right; font-weight:600; padding-right: 8%;"><span
                        style='font-size:18px;color:#707070'>&#9632;</span><span class="learning-month2">００年００月
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <canvas id="compare-training-chart2" width="1355" height="300"
                        style="padding-top:1%; padding-bottom:2%"></canvas>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="text-align:center; font-weight: 800;">正解率</span>
                </td>
            </tr>
            <tr>
                <td> &nbsp;</td>
            </tr>
            <tr>
                <td> &nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src='{{ asset('assets/js/admin/functions/loading.js') }}' type="text/javascript"></script>
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
<script src='{{ asset('assets/js/admin/compare-learning-analysis.js') }}' type="text/javascript"></script>
