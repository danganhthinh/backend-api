
$(document).ready(function () {
    let keyRemove = [
        "current_group_page",
        "current_school_page",
        "current_mentor_page",
        "current_user_page",
        "searchGroup",
        "searchSchool",
        "searchMentor",
        "school_id",
        "group_id",
        "grade_id",
        "searchKey"
    ];
    for (key of keyRemove) {
        localStorage.removeItem(key);
    }
    let current_month = new Date().getMonth().toString();
    $('#learning-select-month1').datepicker({
        changeYear: true,
        format: "yy" + "年" + "mm" + "月",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        // startDate: '2022',
        // endDate: current_month
    });

    $('#learning-select-month2').datepicker({
        changeYear: true,
        format: "yy" + "年" + "mm" + "月",
        viewMode: "months",
        minViewMode: "months",
        autoclose: true,
        // startDate: '2022',
        // endDate: current_month
    });

    bar_chart();

    function bar_chart() {
        $month1 = $('#learning-select-month1').val();
        $month2 = $('#learning-select-month2').val();
        $('.learning-month1').text($month1);
        $('.learning-month2').text($month2);
        bar_chart_1();
        bar_chart_2();
    }

    function bar_chart_1() {
        var canvas1 = document.getElementById("compare-training-chart");
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 25;
        let bar1_data = [];
        bar1_data.push($('#sum_number_training').val());
        bar1_data.push($('#sum_number_correct_answers').val());
        let bar2_data = [];
        bar2_data.push($('#sum_number_training2').val());
        bar2_data.push($('#sum_number_correct_answers2').val());
        // let bar1_data=['150','125'];
        // let bar2_data=['150','125'];
        // console.log(bar1_data);
        // console.log(bar2_data);
        var old_data = {
            // data: [12, 20],
            data: bar1_data,
            backgroundColor: '#7ee48b',
            borderColor: '#7ee48b',
        };

        var new_data = {
            // data: [5, 12],
            data: bar2_data,
            backgroundColor: '#707070',
            borderColor: '#707070',
        };

        var data = {
            labels: ["トレーニング実施回数", "トレーニング正解回数"],
            datasets: [old_data, new_data]
        };

        var chartOptions1 = {
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    display: false,
                    barPercentage: 0.9,
                    categoryPercentage: 0.2
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var barChart1 = new Chart(canvas1, {
            type: 'bar',
            data: data,
            options: chartOptions1
        });
    }

    function bar_chart_2() {
        var canvas = document.getElementById("compare-training-chart2");
        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 25;
        let bar1_data = [0];
        let bar2_data = [0];
        bar1_data.unshift($('#sum_training_correct_rate').val());
        bar2_data.unshift($('#sum_training_correct_rate2').val());
        // bar1_data.unshift('12');
        // bar2_data.unshift('6');
        var old_data = {
            // data: [12, 0],
            data: bar1_data,
            backgroundColor: '#FCAE71',
            borderColor: '#FCAE71',
        };

        var new_data = {
            // data: [5, 0],
            data: bar2_data,
            backgroundColor: '#707070',
            borderColor: '#707070',
        };

        var data = {
            labels: ["正解率"],
            datasets: [old_data, new_data]
        };

        var chartOptions = {
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
                    barPercentage: 0.9,
                    categoryPercentage: 0.1
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        };

        var barChart = new Chart(canvas, {
            type: 'bar',
            data: data,
            options: chartOptions
        });
    }

    $(document).on('click', '#compare-learning-print', function () {
        printDiv('compare-learning-to_print', '個別学習分析');
    })

    function printDiv(divId, title) {
        let chart1 = document.getElementById('chart1').querySelector('canvas');
        let chart2 = document.getElementById('chart2').querySelector('canvas');

        let mywindow = window.open('個別学習分析', 'PRINT', 'height=650,width=900,top=100,left=150');

        mywindow.document.write(`<html><head><title>${title}</title>`);
        mywindow.document.write('<link rel="stylesheet" media="print" type="text/css" href="/backend/assets/css/print-compare-learning.css">');
        mywindow.document.write("<br><br><br><br>");
        mywindow.document.write("<div class='row form-row' style='margin-left:2px; margin-right: -26px;min-width: 100%; width:1500px'>");
        mywindow.document.write(document.getElementById(divId).innerHTML);
        mywindow.document.write("<br><br><br><br><br><br><br><br><br>");
        mywindow.document.write(" <table class='table table-bordered learning-analysis-table-chart' id='chart1' style='background-color:#F7F7F7; border-radius: 20px; width:fit-content; height:40%; margin:auto;margin-top:5%; box-shadow: 1px 3px 8px #d8d8d8; border:none'> <thead class='table-head'> <tr> <th class='compare-chart' style='vertical-align: middle;'>&nbsp;</th> </tr> </thead> <tbody class='table-body table-body-learning' style='background-color:#F7F7F7'> <tr> <td style='float:right; font-weight:600; padding-right: 8%;'><span style='font-size:18px;color:#7ee48b'>&#9632;</span><span style='vertical-align: middle;' class='learning-month1'>００年００月</span> </td> </tr> <tr> <td style='float:right; font-weight:600; padding-right: 8%;'><span style='font-size:18px;color:#707070'>&#9632;</span><span class='learning-month2'>００年００月 </span> </td> </tr> <tr> <td>");
        mywindow.document.write("<br><img width=1100 height=120 src='" + chart1.toDataURL('image/png') + "'/>");
        mywindow.document.write(" </td> </tr> <tr  style='border:none' > <td  style='border:none' > <span style='padding-right: 41%; font-weight: 800; border:none'>トレーニング実施回数</span> <span style='padding-right: 0%; border:none; font-weight: 800;'>トレーニング正解回数</span> </td> </tr> </tbody> </table>");
        mywindow.document.write("<table class='table table-bordered learning-analysis-table-chart' id='chart2' style='background-color:#F7F7F7;border-radius: 20px; width:fit-content; height:40%; margin:auto;margin-top:5%; box-shadow: 1px 3px 8px #d8d8d8;'> <thead class='table-head'> <tr> <th class='compare-chart' style='border:none; background-color:#FCAE71'>&nbsp;</th> </tr> </thead> <tbody class='table-body table-body-learning' style='background-color:#F7F7F7'><tr> <td style='float:right; font-weight:600; padding-right: 8%;'><span style='font-size:18px;color:#FCAE71'>&#9632;</span><span class='learning-month1'>００年００月</span> </td> </tr> <tr> <td style='float:right; font-weight:600; padding-right: 8%;'><span style='font-size:18px;color:#707070'>&#9632;</span><span class='learning-month2'>００年００月 </span> </td> </tr> <tr> <td>");
        mywindow.document.write("<br><img width=1100 height=120 src='" + chart2.toDataURL('image/png') + "'/>");
        mywindow.document.write("</td> </tr> <tr> <td> <span style='text-align:center; font-weight: 800;'>正解率</span> </td> </tr> </tbody> </table></div>");
        mywindow.document.write('</body></html>');
        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10*/
        setTimeout(function () { mywindow.print(); mywindow.close(); }, 600);
        return true;
    }
})