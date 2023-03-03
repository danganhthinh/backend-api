localStorage.removeItem('learning-school')      // liên quan đến màn thống kê
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
var $checked_4_answer = true;
window.onbeforeunload = function (event) {
    event.preventDefault();
    // if (modal_openning == 1) {
    //     return event.returnValue;
    //     if(event.returnValue){
    //         return delete_all_media();
    //     };
    // }
    // return event.returnValue;
    delete_all_media();
    // return event.returnValue = "Are you sure you want to leave the page?";
    localStorage.removeItem('media');
    localStorage.removeItem('current_question_page');
    localStorage.removeItem('duplicated_file');
    $checked_4_answer = true;

};

let old_file;
let $media_files;
let $media_click = 0;
let $check_excel_file = 0;
let modal_openning = 0;
var duplicated_files = [];


$('#question_media').on('change', function (event) {
    let type = $('#question_type').val();
    let inputFile = document.getElementById('question_media');
    // let fileNameField = document.getElementById('question_media_name');
    // let uploadFileName = event.target.files[0].name;
    // fileNameField.textContent = uploadFileName;
    if (inputFile.files.length > 0) {
        let [file] = inputFile.files
        old_file = inputFile.files[0];
        if (type == 3) {
            illustration_preview.src = URL.createObjectURL(file);
            document.getElementById('illustration_preview').classList.add("fullable");
        } else if (type == 4 || type == 5) {
            $('#illustration_preview').hide();
            $('#video_preview').show();
            var video = document.getElementById('video_preview');
            video.src = URL.createObjectURL(file);
            document.getElementById('video_preview').classList.add("fullable");
        }
        $('#question-error-media').text('\xa0');
        $('#illustration_preview').removeClass('preview-error');
    }
})

function check_4_answers_actions() {
    $('#answer1').val('');
    $('#answer2').val('');
    $('#answer3').val('');
    $('#answer4').val('');
    $('#correct_4_answer').val('1');
    $('#4_answers').show();
    $('#correct_true_false').hide();
    $('#category_question').val(2);
    $('#submit-cancel-buttons').css('margin-top', '97px')

    $checked_4_answer = true;
    $('#answer1').prop('disabled', false);
    $('#answer2').prop('disabled', false);
    $('#answer3').prop('disabled', false);
    $('#answer4').prop('disabled', false);
    $('#correct_4_answer').prop('disabled', false);
    $('#correct_answer').prop('disabled', true);
}

function uncheck_4_answers_actions() {
    $('#4_answers').hide();
    $('#correct_true_false').show();
    $('#category_question').val(1);
    $('#submit-cancel-buttons').css('margin-top', '-11px');

    $checked_4_answer = false;
    $('#answer1').prop('disabled', true);
    $('#answer2').prop('disabled', true);
    $('#answer3').prop('disabled', true);
    $('#answer4').prop('disabled', true);
    $('#correct_4_answer').prop('disabled', true);
    $('#correct_answer').prop('disabled', false);
}

$(document).on('click', '#check-4-answer', function () {
    if ((this).checked == true) {
        check_4_answers_actions();
    } else {
        uncheck_4_answers_actions();
    }
})

$(document).on('click', '#btn-store-question', function () {
    if (vallidate_question() == false) {
        return false;
    };
    var form = $('#form-add-question');
    let type = $('#question_type').val();
    formData = new FormData(form[0]);
    // console.log(formData);
    if (($("#question_media").length > 0)) {
        if ($('#question_media')[0].files[0]) {  //check if input file đó có file không (trường hợp chọn lại file rồi ấn cancel)
            var file = $('#question_media')[0].files[0];
        } else {
            var file = old_file;
        }
        if (type == 3) {
            formData.append('image', file);
        } else if (type == 4 || type == 5) {
            formData.append('video', file);
        }
    }
    ///
    let category_question = $('#category_question').val();
    formData.append('category_question', category_question);
    ///
    $.ajax({
        global: true,
        method: "post",
        url: "/admin/question",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            success_toast(data.data)
            clear_form();
            refetch_data();
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.code == 400) {
                error_toast(err.message);
            }
            if (err.errors) {
                $.each(err.errors, function (key, value) {
                    error_toast(value)
                });
            }
        }
    })
})

$(document).on('click', '#btn-mass-add', function () {
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("modal-import-close")[0];
    modal.style.display = "block";
    modal_openning = 1;
    span.onclick = function () {
        if (confirm_close_modal()) {
            delete_all_media();
            modal.style.display = "none";
            clear_modal();
            modal_openning = 0;
        }
    }
})

$(document).on('click', '#sample_question', function () {
    window.onbeforeunload = null;
    window.location.href = "/excel/(sample question) 問題の一括登録_v2.0.xlsx";
    window.onbeforeunload = null;
})

function confirm_close_modal() {
    if ($media_files != null && vallidate_mass() == true) {
        swal({
            title: "終了を続けると、インポートされたファイルは削除されます",
            type: "warning",
            buttons: true,
            showCancelButton: true,
            cancelButtonText: "キャンセル",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "削除",
            closeOnConfirm: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                delete_all_media();
                document.getElementById("myModal").style.display = "none";
                clear_modal();
                modal_openning = 0;
            } else {
                return false;
            }
        })
        // if (window.confirm('終了を続けると、インポートされたファイルは削除されます')) {
        //     return true;
        // } else return false;
    }
    else return true;
}

$(document).on('click', '#button-upload-mass-media', function () {
    document.getElementById("mass-import-media").click();
})

$('#mass-import-media').on('change', function (event) {
    // $('#media_names').html('');
    $('#media_names').show();
    let files = event.target.files;
    $media_click++;
    for (var i = 0; i < files.length; i++) {
        let index = i + '_' + $media_click;
        if (['image/png', 'image/jpeg', 'image/jpg'].includes(files[i].type)) {
            $('#media_names').append('<div class="media_preview" data-duplicate="' + $media_click + '" data-name="' + files[i].name + '" data-id="' + index + '"><img style="object-fit: cover" height=50 width=70 id="mass_media_preview_' + index + '" src="/backend/images/user.png"><span class="media-preview-name">' + files[i].name + '</span><img height=20 data-duplicate="' + $media_click + '" data-name="' + files[i].name + '" data-id="' + index + '" width=20 class="media-trash-icon" src="/backend/images/icons/bin.png"></div>')
        } else if (['video/mp4'].includes(files[i].type)) {
            $('#media_names').append('<div class="media_preview" data-duplicate="' + $media_click + '" data-name="' + files[i].name + '" data-id="' + index + '"><video style="object-fit: cover;vertical-align: middle; height:50px; width:70px" controls><source id="mass_media_preview_' + index + '"  type="video/mp4"></video><span class="media-preview-name">' + files[i].name + '</span><img height=20 data-duplicate="' + $media_click + '" data-name="' + files[i].name + '" data-id="' + index + '" width=20 class="media-trash-icon" src="/backend/images/icons/bin.png"></div>')
        }
        var mass_media_preview = document.getElementById('mass_media_preview_' + index);
        mass_media_preview.src = URL.createObjectURL(files[i]);
    }
    $media_files = Array.from($('#mass-import-media')[0].files);

    // create new input file ( to upload file multiple times )
    $(this).attr('id', '');
    $(this).append('<input type="file" id="mass-import-media" name="file_medias" accept=".png,.jpeg,.jpg,.mp4" multiple style="display:none;" />')

    vallidate_mass();
    let error_file_ids = [];
    if (vallidate_mass() != true) {
        error_file_ids = vallidate_mass();
    }
    upload_media(error_file_ids);
    if ($check_excel_file == 1) {
        if (vallidate_mass() != true) {
            $('#button-upload-ok').prop("disabled", true);
            $('#button-upload-ok').removeClass("button-upload-ok-enabled");
        } else {
            $('#button-upload-ok').prop("disabled", false);
            $('#button-upload-ok').addClass("button-upload-ok-enabled");
        }
    }
})

function upload_media(error_file_ids) {
    filenames = new FormData();
    var length = $media_files.length;
    for (var i = 0; i < length; i++) {
        if (error_file_ids.includes(i) == false) {
            filenames.append("filenames[]", $media_files[i]);
        }
    }
    $.ajax({
        global: true,
        async: true,
        method: "post",
        url: "/admin/question/storeMultipleFile",
        data: filenames,
        processData: false,
        contentType: false,
        success: function (data) {
        },
        error: function (xhr, status, error) {
            if (xhr.status === 400) {
                localStorage.setItem('duplicated_file', '1');
                let json = JSON.parse(xhr.responseText).message;
                for (var i in json) {
                    duplicated_files.push(json[i])
                }
                duplicated_files.forEach(element => {
                    $('.media_preview[data-name="' + element + '"][data-duplicate="' + $media_click + '"]').css('border', '1px solid red');
                    $('.media-trash-icon[data-name="' + element + '"][data-duplicate="' + $media_click + '"]').addClass('mass-media-trash-red');
                });
                $('#button-upload-ok').prop("disabled", true);
                $('#button-upload-ok').removeClass("button-upload-ok-enabled");
            }
        }
    })
}

$(document).on('click', '.media-trash-icon', function () {
    let name = [];
    name.push($(this).attr('data-name'));
    let id = $(this).attr('data-id');
    $('.media_preview[data-id=' + id + ']').hide();
    if (!duplicated_files.includes($(this).attr('data-name'))) {
        $.ajax({
            global: true,
            async: false,
            method: "post",
            url: "/admin/question/destroyMultipleFile",
            data: {
                'names': name,
            }
        })
    }
    delete $media_files[id];
    if ($('#mass-import')[0].files[0] != null && $(".mass-media-trash-red:visible").length == 0) {
        $('#button-upload-ok').prop("disabled", false);
        $('#button-upload-ok').addClass("button-upload-ok-enabled");
    }
})

function delete_all_media() {
    let names = [];
    names = $('.media-trash-icon').map(function () {
        return $(this).attr("data-name");
    }).toArray();
    names = names.filter(function (el) {
        return duplicated_files.indexOf(el) < 0;
    });
    $.ajax({
        async: true,
        global:false,
        method: "post",
        url: "/admin/question/destroyMultipleFile",
        data: {
            'names': names
        },
        success: function (data) {
        }
    })
}

$(document).on('click', '#button-upload-mass', function () {
    document.getElementById("mass-import").click();
})

$('#mass-import').on('click', function (event) {
    $(this).val('')
})

$('#mass-import').on('change', function (event) {
    let fileNameField = document.getElementById('mass-import-name');
    let uploadFileName = event.target.files[0].name;
    fileNameField.textContent = uploadFileName;
    if ($media_files != null && vallidate_mass() != true) {
        $('#button-upload-ok').prop("disabled", true);
        $('#button-upload-ok').removeClass("button-upload-ok-enabled");
    } else {
        $('#button-upload-ok').prop("disabled", false);
        $('#button-upload-ok').addClass("button-upload-ok-enabled");
    }
    $check_excel_file = 1;
})

$(document).on('click', '#btn-cancel-question', function () {
    clear_form();
    $('#btn-update-question').hide();
    $('#btn-store-question').show();
})

$(document).on('click', '#button-upload-ok', function () {
    formData = new FormData();
    var file = $('#mass-import')[0].files[0];
    formData.append('file_excel', file);
    $.ajax({
        async: true,
        method: "post",
        url: "/admin/import-question-excel",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            success_toast(data.data);
            refetch_data();
            clear_modal();
            document.getElementById("myModal").style.display = "none";
        },
        error: function (xhr, status, error) {
            if (xhr.status === 400) {
                excel_toast(JSON.parse(xhr.responseText).message);
            }
            if (xhr.status === 422) {
                error_toast('ファイルの形式が無効です。');
            }
            var err = JSON.parse(xhr.responseText);
            if (err.errors && xhr.status !== 422) {
                $.each(err.errors, function (key, value) {
                    error_toast(value)
                });
            }
        }
    })
})

function vallidate_mass() {
    let e = 0;
    let error_file_ids = [];
    $medias = $media_files;
    // console.log($medias);
    if ($medias.length != 0) {
        for (var i = 0; i < $medias.length; i++) {
            if ($medias[i] != null) {
                let index = i + '_' + $media_click;
                var fsize = $medias[i].size;
                if (!['image/png', 'image/jpeg', 'image/jpg', 'video/mp4'].includes($medias[i].type)) {
                    $('.media_preview[data-id=' + index + ']').css('border', '1px solid red');
                    $('.media-trash-icon[data-id=' + index + ']').addClass('mass-media-trash-red')
                    e++;
                    error_file_ids.push(i);
                } else if (['image/png', 'image/jpeg', 'image/jpg'].includes($medias[i].type) && Math.round((fsize / 1024 / 1024)) > 10) {
                    $('.media_preview[data-id=' + index + ']').css('border', '1px solid red');
                    $('.media-trash-icon[data-id=' + index + ']').addClass('mass-media-trash-red')
                    e++;
                    error_file_ids.push(i);
                } else if (['video/mp4'].includes($medias[i].type) && Math.round((fsize / 1024 / 1024)) > 150) {
                    $('.media_preview[data-id=' + index + ']').css('border', '1px solid red');
                    $('.media-trash-icon[data-id=' + index + ']').addClass('mass-media-trash-red')
                    e++;
                    error_file_ids.push(i);
                } else {
                    $('.media_preview[data-id=' + index + ']').css('border', '1px solid silver');
                    $('.media-trash-icon[data-id=' + index + ']').removeClass('mass-media-trash-red')
                }
            }
        }
    }

    // return e
    if (e > 0) {
        // return false;
        return error_file_ids;
    } else return true;
}

function clear_modal() {
    $media_files = null;
    $('#media_names').html('');
    $('#media_names').hide();
    $('#mass-import-name').html('');
    $('#button-upload-ok').prop("disabled", true);
    $('#button-upload-ok').removeClass("button-upload-ok-enabled");
    document.getElementById("mass-import-media").value = "";
    document.getElementById("mass-import").value = "";
}

$(document).on('click', '#btn-edit-question', function () {
    clear_error();
    let type = $('#question_type').val();
    $id = $(this).attr('data-id');
    $category_question = ($("input[name='category_question'][data-id=" + $id + "]").val());
    $media = ($("input[name='media'][data-id=" + $id + "]").val())
    $title = ($("textarea[name='title'][data-id=" + $id + "]").html())
    $question_level = ($("input[name='question_level'][data-id=" + $id + "]").val())
    $subject_id = ($("input[name='subject_id'][data-id=" + $id + "]").val())
    $answer1 = ($("input[name='answer1'][data-id=" + $id + "]").val())
    $answer2 = ($("input[name='answer2'][data-id=" + $id + "]").val())
    $answer3 = ($("input[name='answer3'][data-id=" + $id + "]").val())
    $answer4 = ($("input[name='answer4'][data-id=" + $id + "]").val())
    $correct_answer = ($("input[name='correct_answer'][data-id=" + $id + "]").val())
    $account_id = ($("input[name='account_id'][data-id=" + $id + "]").val())
    $account_name = ($("input[name='account_name'][data-id=" + $id + "]").val())
    $status = ($("input[name='status'][data-id=" + $id + "]").val())
    if ($category_question == 2) {
        $('#check-4-answer').prop('checked', true);
        $('#4_answers').show();
        $('#correct_true_false').hide();
        $('#category_question').val(2);
        $('#submit-cancel-buttons').css('margin-top', '97px')

        $checked_4_answer = true;
        $('#answer1').prop('disabled', false);
        $('#answer2').prop('disabled', false);
        $('#answer3').prop('disabled', false);
        $('#answer4').prop('disabled', false);
        $('#correct_4_answer').prop('disabled', false);
        $('#correct_answer').prop('disabled', true);
        $('#answer1').val($answer1)
        $('#answer2').val($answer2)
        $('#answer3').val($answer3)
        $('#answer4').val($answer4)
        $('#correct_4_answer').val($correct_answer)
    }
    if ($category_question == 1) {
        $('#check-4-answer').prop('checked', false);
        $('#4_answers').hide();
        $('#correct_true_false').show();
        $('#category_question').val(1);
        $('#submit-cancel-buttons').css('margin-top', '-11px');
        $checked_4_answer = false;
        $('#answer1').prop('disabled', true);
        $('#answer2').prop('disabled', true);
        $('#answer3').prop('disabled', true);
        $('#answer4').prop('disabled', true);
        $('#correct_4_answer').prop('disabled', true);
        $('#correct_answer').prop('disabled', false);
        $('#correct_answer').val($correct_answer);
    }

    $('#title').val($title.replaceAll('&lt;br /&gt;', ''))
    $('#question_level').val($question_level)
    $('#account_id').val($account_id)
    $('#account_name').val($account_name)
    $('#subject_id').val($subject_id)
    $('#status').val($status)
    // alert($category_question)
    if ($media) {
        if (type == 3) {
            illustration_preview.src = "/storage/" + $media;
            document.getElementById('illustration_preview').classList.add("fullable");
        } else if (type == 4 || type == 5) {
            $('#illustration_preview').hide();
            $('#video_preview').show();
            video_preview.src = "/storage/" + $media;
            document.getElementById('video_preview').classList.add("fullable");
        }
    }
    localStorage.setItem('media', $media)
    $('#btn-store-question').hide();
    $('#btn-update-question').show();
    document.getElementById("btn-update-question").setAttribute('data-id', $id);
    console.log(($(".scroll_to").offset().top));
    $('.app-content').animate({
        scrollTop: $(".scroll_to").offset().top
    },
        'medium');
        // $(window).scrollTop( $(".form-vr").offset().top );
})

$(document).on('click', '#btn-update-question', function () {
    let type = $('#question_type').val();
    if (vallidate_question() == false) {
        return false;
    };
    let id = $(this).attr('data-id');
    var form = $('#form-add-question');
    let formData = new FormData(form[0]);
    formData.append('id', id);
    if (($("#question_media").length > 0)) {
        if ($('#question_media')[0].files[0]) {  //kiểm tra input file đó có file không (trường hợp chọn lại file rồi ấn cancel)
            var file = $('#question_media')[0].files[0];
        } else {
            var file = old_file;
        }
        if (type == 3) {
            formData.append('image', file);
        } else if (type == 4 || type == 5) {
            formData.append('video', file);
        }
    }
    ///
    let category_question = $('#category_question').val();
    formData.append('category_question', category_question);
    $.ajax({
        method: "post",
        url: "/admin/question/update",
        processData: false,
        contentType: false,
        dataType: "json",
        data: formData,
        success: function (data) {
            success_toast(data.data);
            clear_form();
            refetch_data();
            $('#btn-update-question').hide();
            $('#btn-store-question').show();
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors) {
                $.each(err.errors, function (key, value) {
                    error_toast(value)
                });
            }
        }
    })
})

$(document).on('click', '#btn-delete-question', function () {
    $id = $(this).attr('data-id');
    $.ajax({
        method: "delete",
        url: "/admin/question/" + $id,
        data: $id,
        success: function (data) {
            success_toast(data.data);
            refetch_data();
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors) {
                $.each(err.errors, function (key, value) {
                    error_toast(value)
                });
            }
        }
    })
})
function refetch_data() {
    if($('#search-video').val() != '' || $('#video-subject-filter').val() !=''){
        search();
    } else{
        if (localStorage.getItem('current_question_page')) {
            page = localStorage.getItem('current_question_page');
        }
        else page = 1;
        $type = $('#question_type').val();
        $.ajax({
            method: "get",
            url: "/admin/question/fetch?page=" + page,
            data: {
                'type': $type,
            },
            success: function (data) {
                $('#questions_data').html(data)
            },
            error: function (xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                if (err.errors) {
                    $.each(err.errors, function (key, value) {
                        error_toast(value)
                    });
                }
            }
        })
    }
}

function clear_form() {
    let type = $('#question_type').val();
    document.getElementById('form-add-question').reset();
    old_file = null;
    if (type == 4 || type == 5) {
        $('#video_preview').hide();
        $('#illustration_preview').show();
        var video = document.getElementById('video_preview');
        video.pause();
    }
    if ($checked_4_answer == false) {
        $('#check-4-answer').prop('checked', false);
        uncheck_4_answers_actions();
    } else {
        $('#check-4-answer').prop('checked', true);
        check_4_answers_actions();
    }
    $('#illustration_preview').attr('src', '/backend/images/icons/default.png');
    clear_media();
    clear_error();
}

function clear_media() {
    if (document.getElementById('illustration_preview')) {
        document.getElementById('illustration_preview').classList.remove("fullable");
    }
    if (document.getElementById('video_preview')) {
        document.getElementById('video_preview').classList.remove("fullable");
    }
}

function clear_media() {
    if (document.getElementById('illustration_preview')) {
        document.getElementById('illustration_preview').classList.remove("fullable");
    }
    if (document.getElementById('video_preview')) {
        document.getElementById('video_preview').classList.remove("fullable");
    }
}

function clear_error() {
    $('.error-text').text('\xa0');
    localStorage.removeItem('media');
    $('#answer1').removeClass('answer-error-border');
    $('#answer2').removeClass('answer-error-border');
    $('#answer3').removeClass('answer-error-border');
    $('#answer4').removeClass('answer-error-border');
    $('#illustration_preview').removeClass('preview-error');
    $('#title').removeClass('title-error-border');
}

$(document).on('click', '.fullable', function () {
    this.requestFullscreen()
    // $(this).toggleClass('fullscreen');
});

$(document).on('click', '#pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    localStorage.setItem('current_question_page', page);
    refetch_data();
});

function vallidate_question() {
    let e = 0;
    let e2 = 0;
    let type = $('#question_type').val();
    $title = $('#title').val();
    if (!$title.trim()) {
        $('#question-error-title').text('必須フィールドです。');
        $('#title').addClass('title-error-border');
        e++
    } else {
        $('#title').removeClass('title-error-border');
        $('#question-error-title').text('\xa0');
    }
    if (document.getElementById('question_media')) {
        $media = document.getElementById('question_media').files;
        if (localStorage.getItem('media')) {
            if ($media.length == 0 && localStorage.getItem('media') == null && old_file == null) {
                $('#question-error-media').text('必須フィールドです。');
                $('#illustration_preview').addClass('preview-error');
                e++;
                e2++;
            }
            else {
                $('#question-error-media').text('\xa0');
                $('#illustration_preview').removeClass('preview-error');
            }
            if (type == 3 && $media.length != 0 && !['image/png', 'image/jpeg', 'image/jpg'].includes($media[0].type)) {
                $('#question-error-media').text('PNG、JPG、JPEGの画像ファイルをアップロードしてください。');
                $('#illustration_preview').addClass('preview-error');
                e++;
                e2++;
            } else if (type == 4 && $media.length != 0 && !['video/mp4'].includes($media[0].type)) {
                $('#question-error-media').text('MP4の動画ファイルをアップロードしてください。');
                $('#video_preview').addClass('preview-error');
                e++;
                e2++;
            } else if (type == 5 && $media.length != 0 && !['video/mp4'].includes($media[0].type)) {
                $('#question-error-media').text('MP4の動画ファイルをアップロードしてください。');
                $('#video_preview').addClass('preview-error');
                e++;
                e2++;
            }
            else {
                $('#question-error-media').text('\xa0');
                $('#video_preview').removeClass('preview-error');
            }
        } else {
            if ($media.length == 0 && old_file == null) {
                $('#question-error-media').text('必須フィールドです。');
                $('#illustration_preview').addClass('preview-error');
                e++;
                e2++;
            } else if (type == 3 && !['image/png', 'image/jpeg', 'image/jpg'].includes($media[0].type)) {
                $('#question-error-media').text('PNG、JPG、JPEGの画像ファイルをアップロードしてください。');
                $('#illustration_preview').addClass('preview-error');
                e++;
                e2++;
            } else if (type == 4 && !['video/mp4'].includes($media[0].type)) {
                $('#question-error-media').text('MP4の動画ファイルをアップロードしてください。');
                $('#video_preview').addClass('preview-error');
                e++;
                e2++;
            } else if (type == 5 && !['video/mp4'].includes($media[0].type)) {
                $('#question-error-media').text('MP4の動画ファイルをアップロードしてください。');
                $('#video_preview').addClass('preview-error');
                e++;
                e2++;
            }
            else {
                $('#question-error-media').text('\xa0');
                $('#illustration_preview').removeClass('preview-error');
            }
        }
        if ($media.length != 0 && e2 == 0) {
            var fsize = $media[0].size;
            if (type == 3 && Math.round((fsize / 1024 / 1024)) > 10) {
                $('#question-error-media').text('10MB以下の動画ファイルをアップロードしてください。');
                $('#illustration_preview').addClass('preview-error');
                e++
            } else if (type > 3 && type < 6 && Math.round((fsize / 1024 / 1024)) > 150) {
                $('#question-error-media').text('150MB以下の動画ファイルをアップロードしてください。');
                $('#video_preview').addClass('preview-error');
                e++
            }
            else {
                $('#question-error-media').text('\xa0');
                $('#illustration_preview').removeClass('preview-error');
            }
        }
    }
    if (document.getElementById('check-4-answer').checked == true) {
        for (let i = 1; i < 5; i++) {
            if (!$('#answer' + i).val().trim()) {
                $('#answer' + i).addClass('answer-error-border');
                $('#question-error-answer' + i).text('必須フィールドです。');
                e++
            } else {
                $('#answer' + i).removeClass('answer-error-border');
                $('#question-error-answer' + i).text('\xa0');
            }
        }
    }
    if (e > 0) {
        return false;
    }
}

$(document).on('keyup', '#title', function () {
    $('#title').removeClass('title-error-border');
    $('#question-error-title').text('\xa0');
})
$(document).on('keyup', '#answer1', function () {
    $('#answer1').removeClass('answer-error-border');
    $('#question-error-answer1').text('\xa0');
})
$(document).on('keyup', '#answer2', function () {
    $('#answer2').removeClass('answer-error-border');
    $('#question-error-answer2').text('\xa0');
})
$(document).on('keyup', '#answer3', function () {
    $('#answer3').removeClass('answer-error-border');
    $('#question-error-answer3').text('\xa0');
})
$(document).on('keyup', '#answer4', function () {
    $('#answer4').removeClass('answer-error-border');
    $('#question-error-answer4').text('\xa0');
})

$("#video-subject-filter").on("change", function () {
    localStorage.setItem('current_question_page', '1');
    search();
})

$(document).on("click", "#video_search", function (event) {
    event.preventDefault();
    localStorage.setItem('current_question_page', '1');
    search();
})

function search(){
    let page=1;
    if(localStorage.getItem('current_question_page')){
        page=localStorage.getItem('current_question_page')
    }
    let subject=$('#video-subject-filter').val();
    let search_key=$('#search-video').val();
    let type = $('#question_type').val();
    let type_name=''
    if(type==1){
        type_name='text'
    } else if(type==3){
        type_name='image'
    } else if(type==4){
        type_name='2D'
    } else if(type==5){
        type_name='360'
    }
    $.ajax({
        method: "get",
        url: "/admin/question/search?page="+page,
        data: {
            'subject_id': subject,
            'searchKey': search_key,
            'type': type_name,
        },
        success: function (data) {
            $("#questions_data").html(data);
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors) {
                $.each(err.errors, function (key, value) {
                    error_toast(value);
                });
            }
        },
    });
}
