

$(document).on('click', '#compare-learning-print', function () {
    printDiv('personal-learning-to_print', '個別学習分析');
})

function printDiv(divId, title) {
    let chart1 = document.getElementById('chart1').querySelector('canvas');
    let chart2 = document.getElementById('chart2').querySelector('canvas');
    let chart3 = document.getElementById('chart3').querySelector('canvas');
    let mywindow = window.open('個別学習分析', 'PRINT', 'height=650,width=900,top=100,left=150');

    mywindow.document.write(`<html><head><title>${title}</title>`);
    mywindow.document.write('<link rel="stylesheet" media="print" type="text/css" href="/backend/assets/css/print-personal-learning.css">');
    mywindow.document.write("<br>");
    mywindow.document.write("<div class='row form-row' style='margin-left:2px; margin-right: -26px;min-width: 120%; width:1500px'> <div class='table-responsive' style='width:1100px'><table class='table table-bordered'><thead class='table-head'>");
    mywindow.document.write(document.getElementById('personal-learning-to_print-name').innerHTML);
    mywindow.document.write("</thead> <tbody class='table-body table-body-learning' style='background-color: white;'>");
    mywindow.document.write(document.getElementById('personal-learning-to_print-total_level').innerHTML);
    mywindow.document.write('<tr>');
    mywindow.document.write(document.getElementById('personal-learning-to_print-subjects').innerHTML);
    mywindow.document.write("</tr>");
    mywindow.document.write("<br><tr> <td class='td-learning-chart' style='border:none' colspan=4> <table class='table table-bordered learning-analysis-table-chart' id='chart1' style='border-radius: 20px; width:88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;'> <thead class='table-head'> <tr> <th class='learning-bar-chart' style='border:none'>トレーニング実施回数</th> </tr> </thead> <tbody class='table-body'> <tr> <td class='learning-dougnut-chart' style='background-color:#F7F7F7; border-radius:20px'>");
    mywindow.document.write("<br><img width=990 height=120 src='" + chart1.toDataURL('image/png') + "'/>");
    mywindow.document.write("</td> </tr> </tbody> </table> </td> </tr> <br> <tr> <td class='td-learning-chart' style='border:none' colspan=4> <table class='table table-bordered learning-analysis-table-chart' id='chart2' style='border-radius: 20px; width:88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;'> <thead class='table-head'> <tr> <th class='training-bar-chart' style='border:none'>トレーニング正解回数</th> </tr> </thead> <tbody class='table-body'> <tr> <td class='learning-dougnut-chart' style='background-color:#F7F7F7; border-radius:20px'>");
    mywindow.document.write("<br><img width=990 height=120 src='" + chart2.toDataURL('image/png') + "'/>");
    mywindow.document.write("</td> </tr> </tbody> </table> </td> </tr> <br> <tr> <td class='td-learning-chart' style='border:none' colspan=4> <table class='table table-bordered learning-analysis-table-chart' id='chart3' style='border-radius: 20px; width: 88%; height:fit-content; margin:auto; box-shadow: 1px 3px 8px #d8d8d8;'> <thead class='table-head'> <tr> <th class='last-chart' style='border:none'>動画視聴回数推移</th> </tr> </thead> <tbody class='table-body'> <tr> <td class='learning-dougnut-chart' style='background-color:#F7F7F7; border-radius:20px'>");
    mywindow.document.write("<br><img width=990 height=120 src='" + chart3.toDataURL('image/png') + "'/>");
    mywindow.document.write("</td> </tr> </tbody> </table> </td> </tr> </tbody> </table> </div> </div>");
    mywindow.document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>');
    mywindow.document.write('</body></html>');
    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    setTimeout(function () { mywindow.print(); mywindow.close(); }, 1000);
    return true;
}