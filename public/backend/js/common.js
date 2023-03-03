
/* Upload images */
const IMAGE_TYPE = ['png','gif','jpg','jpeg','PNG','GIF','JPG','JPEG'];

// Close error image box
$(".box-img-error .close-box-img").click(function(){
    $('.box-img-error').css('display', 'none');
});

$(".add-new-images").click(function(){
    if($('.add-new-images').hasClass('disable')) return false;
    $('#imageAjax').trigger('click');
});

function displayErrorImages(success, error){
    $(".img-msg.success span").text(success);
    $(".img-msg.fail span").text(error);
    $('.box-img-error').css('display', 'block');
}

function setWithListImageBox(){
    var number = $(".list-images .image-item").length;
    if(number > 1){
        $('.add-new-images.large').removeClass('active');
        $('.add-new-images.small').addClass('active');
    }else{
        $('.add-new-images.large').addClass('active');
        $('.add-new-images.small').removeClass('active');
    }
}

if($(window).width() > 768){
    $(".list-images").sortable({
      items: "> div.image-item",
      cursor: "move",
      opacity: 0.8,
      cancel: ".btn-delete-item-image",
    });
}else{
    // scroll x
    setWithListImageBox();
}

// When click remove an image on the blade
$(document).on('click','.btn-delete-item-image',function(){
    var idEdit   = $("form#vehicles input[name=edit]").val();
    var parent   = $(this).parent();
    var filename = parent.find("input[type=hidden]").val();
    if($(this).hasClass('not')) return false;
    $('.max-image').hide();
    var current_image_number = $(".list-images .image-item").length;
    if (current_image_number == 2) {
        $('.add-new-images').removeClass('none');
        $('.add-new-images').removeClass('disable');
    }
    if(!idEdit){
        var url   = '/admin/ajax/delete-image';
        var size  = $('input[name="size"]').val();
        var path  = $('input[name="path"]').val();
        $.ajax({
            url : url,
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                filename: filename,
                size : size,
                path : path,
            },
            'success':function(response){
            },
        });
    }else{
        var oldList = $("#removeImg").val();
        if(oldList != ""){
            var newList = oldList.split(",");
        }else{
            var newList = [];
        }
        newList.push(filename);
        $("#removeImg").val(newList);
    }
    var list_img_cookie      = $(".cookie-remove-image").text();
    var list_img_cookie_arr  = list_img_cookie.split(",");
    var index_remove         = list_img_cookie_arr.indexOf(filename);
    
    if (index_remove > -1) {
        list_img_cookie_arr.splice(index_remove, 1);
        $(".cookie-remove-image").text(list_img_cookie_arr);
        var expiration = new Date();
        expiration.setTime(expiration.getTime() + (30*24*60*60*1000));
        setCookie('cookie_remove_img', $(".cookie-remove-image").text(), expiration, "/");
    }
    parent.fadeOut(100, function(){
        parent.remove();
        setWithListImageBox();
        var number_img = $('.image-item').length;
        if(number_img == 0){
            $("#something_image").val('');
            $("label#something_image-error").show();
        }
    });
});

// delete image vehicle if exists (unused)
var unsued_img = getCookie('cookie_remove_img');
if(unsued_img){
    var url_rm_un = '/admin/ajax/delete-unused-image';
    var size      = $('input[name="size"]').val();
    var path      = $('input[name="path"]').val();
    $.ajax({
        url : url_rm_un,
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            unsued_img: unsued_img,
            size : size,
            path : path,
        },
        'success':function(response){
        },
    });        
}

// Upload when Chọn ảnhs
$('body').delegate('#imageAjax','change', function(){
    var selectedFile = this.files;
    var current_image_number = $(".list-images .image-item").length;
    var new_image_number = selectedFile.length;
    var window_width = $(window).width();
    if(current_image_number == 31){
        $('.max-image').show();
        return false;
    }
    var check_multi_upload = $('input[name="multiple"]').val();
    var photos_uploaded    = 'photos_uploaded';
    if (check_multi_upload == 1) {
        photos_uploaded = 'photos_uploaded[]';
    }
    // Disable the submit button
    var allow_extension = IMAGE_TYPE;
    var reg_extension = /(?:\.([^.]+))?$/;
    var file_valid = {};

    $.each(selectedFile, function(key, item){
        var ext = reg_extension.exec(item.name)[1];
        if(ext && $.inArray(ext, allow_extension) !== -1 && current_image_number < 31){
            var randID = 'ID'+Math.floor((Math.random() * 10000) + 1);
            file_valid['box-'+randID] = item;
            current_image_number++;
            var html = '';
            html += "<div class='image-item canvas filling opacity' id='box-"+randID+"'>";
            html += "<i class='btn-delete-item-image fa fa-times'></i>";
            html += "<div class='wrap-image'>";
            html += "<img class='img-thumbnail center-block' src='' />";
            html += "<canvas width='200' height='200' id='"+randID+"'></canvas>";
            html += "<input type='hidden' value='' name='"+ photos_uploaded +"' />";
            html += "<div class='progress-bar'><span class='percent-bar'></span></div>";
            html += '</div>';
            html += "</div>";

            if($(".image-item.primary").length){
                $(".image-item.primary").after(html);
            }else{
                $(".list-images").prepend(html);
            }

            setWithListImageBox();

            var canvas = document.getElementById(randID);
            var ctx    = canvas.getContext("2d");
            var cw     = canvas.width;
            var ch     = canvas.height;
            var maxW   = cw;
            var maxH   = ch;

            var img = new Image;
            img.onload = function() {
                canvas.width = cw;
                canvas.height = ch;
                ctx.drawImage(img, 0, 0, cw, ch);
            }
            img.src = URL.createObjectURL(item);
        }
        if(current_image_number >= 31){
            $('.max-image').show();
        }
    });

    // Start uploading
    if (check_multi_upload == 1) {
        $('.add-new-images').removeClass('disable');
    } else {
        $('.add-new-images').addClass('none');
    }
    $('.add-new-images').addClass('disable');
    $('body i.btn-delete-item-image').addClass('not');

    var expiration = new Date();
    expiration.setTime(expiration.getTime() + (30*24*60*60*1000));
    var count_file = Object.keys(file_valid).length;
    var count = count_success = count_error = 0;
    $(".list-img-error").html('');
    $('.box-img-error').css('display', 'none');
    var form = document.getElementById('fm-add-new-images');
    var form_data = new FormData($('form#fm-add-new-images')[0]);
    $.each(file_valid, function(key, item){
        var box_item = $("#"+key);
        var progress_bar = box_item.find(".progress-bar");
        var percent_bar = box_item.find(".percent-bar");
        form_data.append('file', item);
        $.ajax({
            url : '/admin/ajax/upload-my-image',
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            contentType: false,
            cache: false,
            processData:false,
            data : form_data,
            xhr: function(){
                //upload Progress
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded;
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        //update progressbar
                        percent_bar.css("width", + percent +"%");
                    }, true);
                }
                return xhr;
            },
            'success':function(response){
                res = response;
                count++;
                progress_bar.remove();
                box_item.removeClass('opacity filling');
                if(res.success == true){
                    count_success++;
                    var new_name = res.file;
                    box_item.find('input[type=hidden]').val(res.path + '/' + res.file_default);
                    box_item.find('img').attr('src', res.path + '/' + new_name);
                    // Add to cookie to remove if non submit
                    var old_cookie_list = $(".cookie-remove-image").text();
                    if(old_cookie_list != "")
                        var new_cookie_list = old_cookie_list.split(",");
                    else
                        var new_cookie_list = [];
                    new_cookie_list.push(res.path + '/' + new_name);
                    new_cookie_list.push(res.path + '/' + res.file_default);
                    $(".cookie-remove-image").text(new_cookie_list.join(','));
                    setCookie('cookie_remove_img', $("body .cookie-remove-image").text(), expiration, "/");
                    setTimeout(function(){
                        box_item.removeClass('canvas').find('canvas').remove();
                    },1000);
                }else{
                    count_error++;
                    box_item.remove();
                    var html = '';
                    html += "<div class='error-col'><div class='image-item canvas' id='box-"+key+"'>";
                    html += "<canvas width='200' height='200' id='can-"+key+"'></canvas>";
                    html += "</div></div>";
                    $(".list-img-error").append(html);

                    var canvas = document.getElementById('can-'+key);
                    var ctx    = canvas.getContext("2d");
                    var cw     = canvas.width;
                    var ch     = canvas.height;
                    var maxW   = cw;
                    var maxH   = ch;
                    var img    = new Image;
                    img.onload = function() {
                        canvas.width = cw;
                        canvas.height = ch;
                        ctx.drawImage(img,0,0,cw,ch);
                    }
                    img.src = URL.createObjectURL(item);
                    box_item.find('input[type=hidden]').remove();
                    box_item.find('img').remove();
                    box_item.addClass('error-size').find('.btn-delete-item-image').after(res.message);
                    if (current_image_number == 2) {
                        $('.add-new-images').removeClass('none');
                        $('.add-new-images').removeClass('disable');
                    }
                }
                
                if(count == count_file){
                    if(count_error > 0){
                        displayErrorImages(count_success, count_error);
                        setWithListImageBox();
                    }

                    // After uploading
                    if($(".list-images .image-item").length > 0){
                        $("#something_image").val('have image');
                    }else{
                        $("#something_image").val('');
                    }
                    $("label#something_image-error").hide();

                    
                    $('.add-new-image-text').show();
                    $('.add-new-image-text.message').hide();
                    $('body i.btn-delete-item-image').removeClass('not');

                    // Empty input after uploading
                    var emptyImage = $('#imageAjax');
                    emptyImage.wrap('<form>').closest('form').get(0).reset();
                    emptyImage.unwrap();
                    return true;
                }
            }
        });
    });
});
/* End upload images */

/* Set, Get, Delete cookie */
// Set cookie
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
    ((expires == null) ? "" : "; expires=" + expires.toGMTString()) +
    ((path == null) ? "" : "; path=" + path) +
    ((domain == null) ? "" : "; domain=" + domain) +
    ((secure == null) ? "" : "; secure");
}
 
// Read cookie
function getCookie(name){
    var cname = name + "=";
    var dc = document.cookie;
    if (dc.length > 0) {
        begin = dc.indexOf(cname);
        if (begin != -1) {
            begin += cname.length;
            end = dc.indexOf(";", begin);
            if (end == -1) end = dc.length;
            return unescape(dc.substring(begin, end));
        }
    }
    return null;
}
 
//delete cookie
function eraseCookie (name,path,domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
        ((path == null) ? "" : "; path=" + path) +
        ((domain == null) ? "" : "; domain=" + domain) +
        "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}
/* End */

$("input.number").keypress(function(){
    var key_code = window.event.keyCode;
    if(key_code < 48 || key_code > 57)
        return false;
});

$('.number-format').number(true);

/* Config modal */
// let modal = document.getElementById('myModal');
// let modal2 = document.getElementById('myModal2');
// let modal3 = document.getElementById('myModal3');
// window.onclick = function(event) {
//    	if (event.target == modal) {
//        	modal.style.display = 'none';
//     }
//     if (event.target == modal2) {
//         modal2.style.display = 'none';
//     }
//     if (event.target == modal3) {
//         modal3.style.display = 'none';
//     }   
// }

/* Script Calendar */
let previous = 0;
let previousCompare = 0;
const currentMonth = moment().format('MM');
const currentYear = moment().format('YYYY');
const currentQuarter = moment().quarter();
const todayDate = moment().format('DD');
const todayName = moment().format('ddd');
const formatDateOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

function htmlDayOfMonth(subtract = 0, compare = false) {
    // Get moment at start date of previous month
    const prevMonth = moment().subtract(subtract, 'month').startOf('month');
    const prevMonthDays = prevMonth.daysInMonth();
    const monthYearName = `Tháng ${prevMonth.format('MM, YYYY')}`;
    
    // Array to collect dates of previous month
    let prevMonthDates = [];
    for (let i = 0; i < prevMonthDays; i++) {
        // Calculate moment based on start of previous month, plus day offset
        const prevMonthDay = prevMonth.clone().add(i, 'days');
        prevMonthDates.push(prevMonthDay);
    }
    const previous = compare ? 'previousCompare' : 'previous';
    const next = compare ? 'nextCompare' : 'next';
    let html = `<p class="w-100"><i class="fa fa-chevron-left i-left ${previous}" data-type="${DATE}"></i><i class="fa fa-chevron-right i-right ${next}" data-type="${DATE}"></i><span>${monthYearName}</span></p><ul class="ul-date date w-100"><li class="head"><ol class="w-100"><li>T2</li><li>T3</li><li>T4</li><li>T5</li><li>T6</li><li>T7</li><li>CN</li></ol></li><li class="body"><ol class="w-100 ul-date-active">`;
    let count = 0;
    let first = true;
    for (let i = 0; i < prevMonthDates.length; i++) {
        const dateName = prevMonthDates[i].format('ddd');
        const date = prevMonthDates[i].format('DD');
        const dateTimeStamp = prevMonthDates[i].format('X');
        const dateText = prevMonthDates[i].format('DD/MM/YYYY');
        const active = date === todayDate && todayName === dateName && prevMonth.format('YYYY') === currentYear && prevMonth.format('MM') === currentMonth ? `class="active"` : '';
        if (count === 0 && first) {
            first = false;
            for (let k = 0; k < formatDateOfWeek.length; k++) {
                if (dateName !== formatDateOfWeek[k]) {
                    count++;
                } else {
                    break;
                }
            }
            if (count > 0) {
                for(let j = 0; j < count; j++) {
                    html += `<li class="transparent"><span>0</span></li>`;
                }
            }
        }
        html += `<li ${active} data-date="${dateTimeStamp}" data-text="${dateText}"><span>${date}</span></li>`;
    }
    html += `</ol></li></ul>`;
    return html;
}

function htmlWeekOfMonth(subtract)
{   
    // Get moment at start date of previous month
    const prevMonth = moment().subtract(subtract, 'month').startOf('month');
    const year = prevMonth.format('YYYY');
    const monthChoose = prevMonth.format('MM');
    const month = monthChoose - 1;
    const todayDateSingle = moment().format('D');
    const monthYearName = `Tháng ${prevMonth.format('MM, YYYY')}`;

    let weeks = [],
    firstDate = new Date(year, month),
    lastDate = new Date(year, month + 1, 0),
    numDays = lastDate.getDate();

    let start = 1;
    let end = 7 - firstDate.getDay();
    if (firstDate.getDay() === 0) {
        end = 1;
    } else {
        end = 7 - firstDate.getDay() + 1;
    }
    while (start <= numDays) {
        weeks.push({start: start, end: end});
        start = end + 1;
        end = end + 7;
        end = start === 1 && end === 8 ? 1 : end;
        if (end > numDays) {
            end = numDays;
        }
    }
    let html = `<p class="w-100"><i class="fa fa-chevron-left i-left previous" data-type="${WEEK}"></i><i class="fa fa-chevron-right i-right next" data-type="${WEEK}"></i><span>${monthYearName}</span></p><ul class="ul-date ul-date-active week w-100">`;
    let week = 1;
    for (let i = 0; i < weeks.length; i++) {
        const active = weeks[i].start <= todayDateSingle && weeks[i].end >= todayDateSingle && currentMonth == monthChoose && currentYear == year ? `class="active"` : '';
        const dateName = `${weeks[i].start}/${monthChoose} - ${weeks[i].end}/${monthChoose}`;
        const dateStartTimeStamp = moment(`${weeks[i].start}/${monthChoose}/${year}`, 'D/MM/YYYY').format('X');
        const dateEndTimeStamp = moment(`${weeks[i].end}/${monthChoose}/${year}`, 'D/MM/YYYY').format('X');
        const dateText = `${weeks[i].start}/${monthChoose}/${year} - ${weeks[i].end}/${monthChoose}/${year}`;
        html += `<li ${active} data-date="${dateStartTimeStamp}-${dateEndTimeStamp}" data-text="${dateText}"><p>Tuần ${week}</p><span>${dateName}</span></li>`;
        week++;
    }
    html += `</ul>`;
    return html;
}

function htmlMonthsOfYear(subtract, compare = false)
{
    // Get moment at start date of previous year
    const definedMonth = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    const prevYear = moment().subtract(subtract, 'year').startOf('year');
    const yearName = prevYear.format('YYYY');
    const previous = compare ? 'previousCompare' : 'previous';
    const next = compare ? 'nextCompare' : 'next';
    let html = `<p class="w-100"><i class="fa fa-chevron-left i-left ${previous}" data-type="${MONTH}"></i><i class="fa fa-chevron-right i-right ${next}" data-type="${MONTH}"></i><span>${yearName}</span></p><ul class="ul-date month ul-date-active w-100">`;
    for (let i = 0; i <= 11; i++) {
        const active = prevYear.format('YYYY') == currentYear && definedMonth[i] == Number(currentMonth) ? `class="active"` : '';
        const dateTimeStamp = moment(`01/${definedMonth[i]}/${yearName}`, 'DD/M/YYYY').format('X');
        html += `<li ${active} data-date="${dateTimeStamp}" data-text="Tháng ${definedMonth[i]}/${yearName}">Tháng ${definedMonth[i]}</li>`;
    }
    html += `</ul>`;
    return html;
}

function htmlQuartersOfYear(subtract)
{
    // Get moment at start date of previous year
    const prevYear = moment().subtract(subtract, 'year').startOf('year');
    const yearName = prevYear.format('YYYY');
    let html = `<p class="w-100"><i class="fa fa-chevron-left i-left previous" data-type="${QUARTER}"></i><i class="fa fa-chevron-right i-right next" data-type="${QUARTER}"></i><span>${yearName}</span></p><ul class="ul-date quarter week ul-date-active w-100">`;
    for(let i = 1; i <= 4; i++) {
        const startDateName = moment(yearName, 'YYYY').quarter(i).startOf('quarter').format('DD/MM');
        const endDateName = moment(yearName, 'YYYY').quarter(i).endOf('quarter').format('DD/MM');
        const active = currentYear == yearName && Number(currentQuarter) == i ? `class="active"` : '';
        const dateStartTimeStamp = moment(`${startDateName}/${yearName}`, 'DD/MM/YYYY').format('X');
        const dateEndTimeStamp = moment(`${endDateName}/${yearName}`, 'DD/MM/YYYY').format('X');
        const dateText = `${startDateName}/${yearName} - ${endDateName}/${yearName}`;
        html += `<li ${active} data-date="${dateStartTimeStamp}-${dateEndTimeStamp}" data-text="${dateText}"><p>Quý ${i}</p><span>${startDateName} - ${endDateName}</span></li>`;
    }
    html += `</ul>`;
    return html;
}

function htmlYears(subtract)
{
    // Get moment at start date of previous year
    let number = 12 * subtract;
    const prevYear = moment().subtract(number, 'year').startOf('year');
    const yearName = `${moment().subtract(number+11, 'year').startOf('year').format('YYYY')} - ${prevYear.format('YYYY')}`;
    let html = `<p class="w-100"><i class="fa fa-chevron-left i-left previous" data-type="${YEAR}"></i><i class="fa fa-chevron-right i-right next" data-type="${YEAR}"></i><span>${yearName}</span></p><ul class="ul-date month year ul-date-active w-100">`;
    let years = [];
    for(let i = number; i < (number + 12); i++) {
        const year = moment().subtract(i, 'year').startOf('year').format('YYYY');
        years.push(year);
    }
    years.reverse();
    for(let i = 0; i < 12; i++) {
        const active = currentYear == years[i] ? `class="active"` : '';
        const dateTimeStamp = moment(`01/01/${years[i]}`, 'DD/MM/YYYY').format('X');
        html += `<li ${active} data-date="${dateTimeStamp}" data-text="${years[i]}">${years[i]}</li>`;
    }
    html += `</ul>`;
    return html;
}

$(document).ready(function() {
    const dateTypeDefault = $('span.date-type-default').text();
    if (dateTypeDefault) {
        switch (dateTypeDefault) {
            case DATE:
                $('.wrap-calendar .wrap-date').html(htmlDayOfMonth(previous));
                break;
            case WEEK:
                $('.wrap-calendar .wrap-date').html(htmlWeekOfMonth(previous));
                break;
            case MONTH:
                $('.wrap-calendar .wrap-date').html(htmlMonthsOfYear(previous));
                break;
            case QUARTER:
                $('.wrap-calendar .wrap-date').html(htmlQuartersOfYear(previous));
                break;
            case YEAR:
                $('.wrap-calendar .wrap-date').html(htmlYears(previous));
                break;                
        }
    } else {
        $('.wrap-calendar .wrap-date').html(htmlDayOfMonth(previous));
    }
    
})

$('.wrap-compare .wrap-date').html(htmlDayOfMonth(previousCompare, true));

$('body').on('click', '.wrap-calendar i.previous', function () {
    previous++;
    const type = $(this).data('type');
    const parent = $(this).closest('div');
    parent.empty();
    switchTypeDate(type, previous, parent);
});

$('body').on('click', '.wrap-calendar i.previousCompare', function () {
    previousCompare++;
    const type = $(this).data('type');
    const parent = $(this).closest('div');
    parent.empty();
    switchTypeDate(type, previousCompare, parent);
});

$('body').on('click', '.wrap-calendar i.next', function () {
    previous--;
    const type = $(this).data('type');
    const parent = $(this).closest('div');
    parent.empty();
    switchTypeDate(type, previous, parent);
});

$('body').on('click', '.wrap-calendar i.nextCompare', function () {
    previousCompare--;
    const type = $(this).data('type');
    const parent = $(this).closest('div');
    parent.empty();
    switchTypeDate(type, previousCompare, parent);
});

$('body').on('click', '.nav-calendar li', function () {
    previous = 0;
    previousCompare = 0;
    const type = $(this).data('type');
    const parent = $(this).closest('.wrap-calendar');
    if (!$(this).hasClass('active')) {
        parent.find('.wrap-date').empty();
        parent.find('.nav-calendar > li').removeClass('active');
        parent.find('.wrap-compare ul.parent li i').removeClass('active');
        $(this).addClass('active');
        $('input.input_compare_date').val('');
        $('input.input_compare_type').val('');
        switchTypeDate(type, previous, parent.find('.wrap-date.col'));
        parent.find('.wrap-compare .wrap-date').css('display', 'none');
        switchTypeDate(type, previous, parent.find('.wrap-compare .wrap-date'));
        $('.wrap-compare .wrap-content ul.parent').removeClass('active');
        $(`.${type}`).addClass('active');
        parent.find('.wrap-compare p.title').css('display', 'none');
        
        let date = '';
        switch (type) {
            case DATE:
                parent.find('.wrap-date.not-compare ul.ul-date.date li.body ol li').each(function () {
                    if ($(this).hasClass('active')) {
                        date += $(this).data('date');
                    }
                });
                break;
            case WEEK:
                parent.find('.wrap-date.not-compare ul.ul-date-active.week li').each(function () {
                    if ($(this).hasClass('active')) {
                        date += $(this).data('date');
                    }
                })
                break;
            case MONTH:
                parent.find('.wrap-date.not-compare ul.ul-date.month li').each(function () {
                    if ($(this).hasClass('active')) {
                        date += $(this).data('date');
                    }
                });
                break; 
            case QUARTER:
                parent.find('.wrap-date.not-compare ul.ul-date.quarter li').each(function () {
                    if ($(this).hasClass('active')) {
                        date += $(this).data('date');
                    }
                });
                break;
            case YEAR:
                parent.find('.wrap-date.not-compare ul.ul-date.year li').each(function () {
                    if ($(this).hasClass('active')) {
                        date += $(this).data('date');
                    }
                });
                $('input.input_compare_date').val(date);
                $('input.input_compare_type').val(COMPARE_YEAR);
                parent.find('.wrap-compare p.title').css('display', 'none');
                break;               
        }
        $('.item-date input.input_date').val(date);
        $('.item-date input.input_date_type').val(type);
    }
});

function switchTypeDate(type, previous, e)
{
    const compare = e.data('type') == 'compare' ? true : false;
    switch (type) {
        case DATE: 
            e.html(htmlDayOfMonth(previous, compare));
            break;
        case WEEK:
            e.html(htmlWeekOfMonth(previous));
            break;
        case MONTH:
            e.html(htmlMonthsOfYear(previous, compare));
            break;
        case QUARTER:
            e.html(htmlQuartersOfYear(previous));
            break;
        case YEAR:
            e.html(htmlYears(previous));
            break;
    }
}

function replaceCharacter(text)
{
    text = text.replace(/^,+/, '');
    text = text.replace(/,$/,'');
    return text;
}
/* End Script Calendar */

function getColorByArray(length, data = [])
{
    const color = COLORS;
    let dataCheck = [];
    if (length > 0) {
        for (let i = 0; i < length; i++) {
            for (const [key, value] of Object.entries(color)) {
                if (!dataCheck.includes(key)) {
                    data.push(value);
                    dataCheck.push(key);
                    break;
                }
            }
        }
        if (length > Object.keys(color).length && length > data.length) {
            data = getColorByArray(length, data);
        }
    }
    return data;
}

function ucwords (str) {
    return (str + '')
      .replace(/^(.)|\s+(.)/g, function ($1) {
        return $1.toUpperCase()
      })
}

function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
        callback.apply(context, args);
        }, ms || 0);
    };
}

function truncate(str, n){
    return (str.length > n) ? str.substr(0, n-1) + '…' : str;
};

function hasWhiteSpace(str) {
    return str.trim(' ').length;
}

$('body').on('click', '.wrap-ul-expenses-categories li.parent i.fa-chevron-up', function () {
    const parent = $(this).closest('li.parent');
    const _id = parent.data('id');
    $('.class'+_id).trigger('click');
    $('.class'+_id).css('display', 'flex');
})

$('body').on('click', '.wrap-title', function () {
    const _id = $(this).data('id');
    $(this).css('display', 'none');
});

// Create our number formatter.
const formatter = new Intl.NumberFormat('vi-VI', {
    style: 'currency',
    currency: 'VND',
});

$('.modal-content').scroll(function () {
    const top = $(this).scrollTop();
    if (top == 0) {
        $(this).find('.wrap-button').css('bottom', `15px`);
        $(this).find('button.m-close').css('top', `5px`);
    } else {
        const newTop = 15 - top;
        const newTop2 = 5 + top;
        $(this).find('.wrap-button').css('bottom', `${newTop}px`);
        $(this).find('button.m-close').css('top', `${newTop2}px`);
    } 
})

function capitalizeText(str) {
    let strVal = '';
    str = str.split(' ');
    for (var chr = 0; chr < str.length; chr++) {
        strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' ';
    }
    return strVal.replace(' đ', ' Đ', strVal);
}

$('body').on('click', '.close.m-close', function () {
    const parent = $(this).closest('.modal');
    parent.css('display', 'none');
    if (parent.hasClass('modal1')) {
        $('#submit-img-pay .btn-danger').trigger('click');
        $('#acceptPay .btn-danger').trigger('click');
    }
})