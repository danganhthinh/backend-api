<div class="row form-row" style="margin-left:2px; margin-right: -26px;min-width: 100%; width:1500px">
    <div class="table-responsive" style="width:1500px">
        <table class="table table-bordered">
            <thead class="table-head">
                <tr id="personal-learning-to_print-name" class="sticky-thead">
                    <th class="personal-learning-analysis-title" style="border:none" colspan=4>生徒氏名 <b
                            class="learning_student_name" id='student_name'>{{ $full_name }}</b></th>
                </tr>
            </thead>
            <tbody class="table-body table-body-learning" style="background-color: white;">
                <tr id="personal-learning-to_print-total_level" style='display: flex;'>
                    <td style="width:97px">&nbsp;</td>
                    <td class="learning-title" colspan=4 style="width:1285px"> <span
                            style="font-size: 25px; font-weight: 600;">全体ランク&emsp;<b id='total_level'>
                                <span style="font-size:200%">
                                    @if ($level_total)
                                        {{ $level_total }}
                                    @else
                                        0
                                    @endif
                                </span>級
                            </b></span>
                    </td>
                    <td style="width:16px">&nbsp;</td>
                </tr>
                <tr>
                    <td id="personal-learning-to_print-subjects" class="td-learning-chart" style="border:none"
                        colspan=4>
                        <table class="table table-bordered"
                            style="width:fit-content; height:fit-content; margin:auto; border:none;background-color: transparent;">
                            <tbody class="table-body table-body-learning" style="background-color: transparent;">
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <?php $i = 0; ?>
                                @foreach ($level_subject as $subject)
                                    @if (array_search($subject, $level_subject) % 4 == 0)
                                        <?php $i = array_search($subject, $level_subject); ?>
                                        <tr>
                                    @endif
                                    <td class="learning-items">
                                        <span><span style="font-size: 200%"><b
                                                    class="learning-subject-level">{{ $subject['level_subject'] }}</b></span>級</span>
                                        <p class="learning-subject-name">{{ $subject['subject_name'] }}</p>
                                    </td>
                                    @if (array_search($subject, $level_subject) == $i + 4)
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        </td>
        </tr>
        <tr>
            <td class="td-learning-chart" style="border:none" colspan=4>
                <table class="table table-bordered learning-analysis-table-chart" id="chart1"
                    style="border-radius: 20px; width:88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;">
                    <thead class="table-head">
                        <tr>
                            <th class="learning-bar-chart" style="border:none">トレーニング実施回数</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <tr>
                            <td class="learning-dougnut-chart" style="background-color:#F7F7F7; border-radius:20px">
                                <canvas id="learning-accuracy-rate" style="width:240%; height:650px"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td-learning-chart" style="border:none" colspan=4>
                <table class="table table-bordered learning-analysis-table-chart" id="chart2"
                    style="border-radius: 20px; width:88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;">
                    <thead class="table-head">
                        <tr>
                            <th class="training-bar-chart" style="border:none">トレーニング正解回数</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <tr>
                            <td class="learning-dougnut-chart" style="background-color:#F7F7F7; border-radius:20px">
                                <canvas id="training-accuracy-rate" style="width:240%; height:650px"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td-learning-chart" style="border:none" colspan=4>
                <table class="table table-bordered learning-analysis-table-chart" id="chart3"
                    style="border-radius: 20px; width: 88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;">
                    <thead class="table-head">
                        <tr>
                            <th class="last-chart" style="border:none">動画視聴回数推移</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <tr>
                            <td class="learning-dougnut-chart" style="background-color:#F7F7F7; border-radius:20px">
                                <canvas id="last-chart" style="width:240%; height:650px"></canvas>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
        </table>
    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<script>
    // var time_periods = ["1", "2", "3", "4", "5", '6', '7', '8', '9', '10', '11', '12'];
    // var data_training = [12, 20, 21, 22, 31, 0, 32, 23, 11, 09, 31, 38];
    // var data_training_correct =  [12, 0, 21, 1, 31, 2, 32, 23, 11, 09, 31, 38];
    // var data_video =  [12, 20, 21, 0, 31, 24, 32, 3, 11, 09, 31, 38];
    // var xValues = data_training;
    var time_periods = [];
    var periods = {!! json_encode($time_periods) !!};
    periods.forEach(period => {
        time_periods.push(period['month']);
    })
    var data_training = {!! json_encode($data_training) !!};
    var data_training_correct_decimal = {!! json_encode($data_training_correct) !!};
    var data_training_correct = [];
    for (var i = 0; i < data_training_correct_decimal.length; i++) {
        data_training_correct.push(data_training_correct_decimal[i].toFixed(4) * 100);
    }
    var data_video = {!! json_encode($data_video) !!};

    new Chart("learning-accuracy-rate", {
        type: "bar",
        data: {
            labels: time_periods,
            datasets: [{
                backgroundColor: '#707070',
                data: data_training
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: false,
                    barPercentage: 0.5
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }],
            }
        }
    });
    new Chart("training-accuracy-rate", {
        type: "bar",
        data: {
            labels: time_periods,
            datasets: [{
                backgroundColor: '#FCAE71',
                data: data_training_correct
            }]
        },
        options: {
            tooltips: {
                callbacks: {
                    label: (item) => `${item.yLabel} %`,
                },
            },
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: false,
                    barPercentage: 0.5
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }],
            }
        }
    });
    new Chart("last-chart", {
        type: "bar",
        data: {
            labels: time_periods,
            datasets: [{
                backgroundColor: '#07C5D3',
                data: data_video
            }]
        },
        options: {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: false,
                    barPercentage: 0.5
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }],
            }
        }
    });
</script>
