localStorage.removeItem("learning-school"); // liên quan đến màn thống kê
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
window.onbeforeunload = function () {
    localStorage.removeItem("media");
    localStorage.removeItem("thumbnail");
    localStorage.removeItem("current_video_page");
};

let old_file;
let old_file2;

$(document).on('keyup', '#video-title-input', function () {
    $('#video-title-input').removeClass('video-title-error-border');
    $('#video-title-input').addClass('video-title-input');
    $('#question-error-title').text('\xa0');
})

$("#video-subject-filter").on("change", function () {
    localStorage.setItem('current_video_page', '1');
    search();
})

$(document).on("click", "#video_search", function (event) {
    event.preventDefault();
    localStorage.setItem('current_video_page', '1');
    search();
})

function search(){
    let page=1;
    if(localStorage.getItem('current_video_page')){
        page=localStorage.getItem('current_video_page')
    }
    let subject=$('#video-subject-filter').val();
    let search_key=$('#search-video').val();
    $.ajax({
        method: "get",
        url: "/admin/video/search?page="+page,
        data: {
            'subject_id': subject,
            'searchKey': search_key,
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

$("#question_media").on("change", function (event) {
    let inputFile = document.getElementById("question_media");
    let fileNameField = document.getElementById("question_media_name");
    let uploadFileName = event.target.files[0].name;
    fileNameField.textContent = uploadFileName;
    if (inputFile.files) {
        let [file] = inputFile.files;
        old_file = inputFile.files[0];
        $("#illustration_preview").hide();
        $("#video_preview").show();
        var video = document.getElementById("video_preview");
        video.src = URL.createObjectURL(file);
        document.getElementById("video_preview").classList.add("fullable");
        $("#question-error-media").text("\xa0");
        $("#illustration_preview").removeClass("preview-video-error");
        $("#video_preview").removeClass("preview-video-error");
    }
});

$("#video_thumbnail").on("change", function (event) {
    let inputFile2 = document.getElementById("video_thumbnail");
    let fileNameField = document.getElementById("question_thumbnail_name");
    let uploadFileName = event.target.files[0].name;
    fileNameField.textContent = uploadFileName;
    if (inputFile2.files) {
        let [file] = inputFile2.files;
        old_file2 = inputFile2.files[0];
        var thumbnail = document.getElementById("thumbnail_preview");
        thumbnail.src = URL.createObjectURL(file);
        document.getElementById("thumbnail_preview").classList.add("fullable");
        $("#question-error-thumbnail").text("\xa0");
        $("#thumbnail_preview").removeClass("preview-video-error");
    }
});

$(document).on("click", "#btn-store-question", function () {
    if (vallidate() == false) {
        return false;
    }
    var form = $("#form-add-question");
    formData = new FormData(form[0]);
    if ($("#question_media").length > 0) {
        if ($("#question_media")[0].files[0]) {
            var file = $("#question_media")[0].files[0];
        } else {
            var file = old_file;
        }
        formData.append("video", file);
    }
    if ($("#video_thumbnail").length > 0) {
        if ($("#video_thumbnail")[0].files[0]) {
            var file2 = $("#video_thumbnail")[0].files[0];
            formData.append("thumbnail", file2);
        } else {
            var file2 = old_file2;
        }
    }
    $title = $("#video-title-input").val();
    formData.append("title", $title);
    $.ajax({
        method: "post",
        url: "/admin/video",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            success_toast(data.data);
            clear_form();
            refetch_data();
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
});

$(document).on("click", "#btn-mass-add", function () {
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("modal-import-close")[0];
    modal.style.display = "block";
    span.onclick = function () {
        modal.style.display = "none";
    };
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});

$(document).on("click", "#button-upload-mass", function () {
    document.getElementById("mass-import").click();
});

$("#mass-import").on("change", function (event) {
    let fileNameField = document.getElementById("mass-import-name");
    let uploadFileName = event.target.files[0].name;
    fileNameField.textContent = uploadFileName;
    $("#button-upload-ok").prop("disabled", false);
    $("#button-upload-ok").addClass("button-upload-ok-enabled");
});

$(document).on("click", "#btn-cancel-question", function () {
    clear_form();
    $("#btn-update-video").hide();
    $("#btn-store-question").show();
});

$(document).on("click", "#btn-edit-video", function () {
    clear_error();
    $id = $(this).attr("data-id");
    $title = $("input[name='title'][data-id=" + $id + "]").val();
    $media = $("input[name='media'][data-id=" + $id + "]").val();
    $thumbnail = $("input[name='thumbnail'][data-id=" + $id + "]").val();
    $video_level = $("input[name='video_level'][data-id=" + $id + "]").val();
    $subject_id = $("input[name='subject_id'][data-id=" + $id + "]").val();
    $account_id = $("input[name='account_id'][data-id=" + $id + "]").val();
    $account_name = $("input[name='account_name'][data-id=" + $id + "]").val();
    $status = $("input[name='status'][data-id=" + $id + "]").val();
    $("#video_level").val($video_level);
    $("#account_id").val($account_id);
    $("#account_name").val($account_name);
    $("#subject_id").val($subject_id);
    $("#status").val($status);
    $("#video-title-input").val($title);
    $("#question_media_name").html($media);
    $("#question_thumbnail_name").html($thumbnail);
    if ($media) {
        $("#illustration_preview").hide();
        $("#video_preview").show();
        video_preview.src = "/storage/" + $media;
        document.getElementById("video_preview").classList.add("fullable");
    }
    localStorage.setItem("media", $media);
    if ($thumbnail) {
        thumbnail_preview.src = "/storage/" + $thumbnail;
        document.getElementById("thumbnail_preview").classList.add("fullable");
    } else {
        $("#thumbnail_preview").attr(
            "src",
            "/backend/images/icons/default.png"
        );
        $("#question_thumbnail_name").text("");
    }
    localStorage.setItem("thumbnail", $thumbnail);
    $("#btn-store-question").hide();
    $("#btn-update-video").show();
    document.getElementById("btn-update-video").setAttribute("data-id", $id);
    $(".app-content").animate(
        {
            scrollTop: $(".add-video").offset().top,
        },
        "medium"
    );
});

$(document).on("click", "#btn-update-video", function () {
    if (vallidate() == false) {
        return false;
    }
    let id = $(this).attr("data-id");
    var form = $("#form-add-question");
    let formData = new FormData(form[0]);
    formData.append("id", id);
    if ($("#question_media").length > 0) {
        if ($("#question_media")[0].files[0]) {
            var file = $("#question_media")[0].files[0];
        } else {
            var file = old_file;
        }
        formData.append("video", file);
        $title = $("#video-title-input").val();
        formData.append("title", $title);
    }
    if ($("#video_thumbnail").length > 0) {
        if ($("#video_thumbnail")[0].files[0]) {
            var file2 = $("#video_thumbnail")[0].files[0];
            formData.append("thumbnail", file2);
        } else {
            var file2 = old_file2;
        }
    }
    $.ajax({
        method: "post",
        url: "/admin/video/update",
        processData: false,
        contentType: false,
        dataType: "json",
        data: formData,
        success: function (data) {
            success_toast(data.data);
            clear_form();
            refetch_data();
            $("#btn-update-video").hide();
            $("#btn-store-question").show();
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
});

$(document).on("click", "#btn-delete-video", function (e) {
    $id = $(this).attr("data-id");
    e.preventDefault();
    swal(
        {
            title: "このビデオを削除してもよろしいですか？",
            text: "削除の確認?",
            type: "warning",
            buttons: true,
            showCancelButton: true,
            cancelButtonText: "キャンセル",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "削除",
            closeOnConfirm: false,
        },
        // ajax_delete()
        function () {
            if ($(".table-videos").children().length === 1) {
                localStorage.setItem('delete1', '1');
            }
            $.ajax({
                type: "DELETE",
                url: "/admin/video/" + $id,
                data: $id,
                dataType: "html",
                success: function (data) {
                    // success_toast(data.data);
                    refetch_data();
                    clear_form();
                    $('#btn-update-video').hide();
                    $('#btn-store-question').show();
                    localStorage.removeItem('delete1')
                },

            })
                .done(function () {
                    swal(
                        "削除された!",
                        "データが正常に削除されました!",
                        "success"
                    );
                })
                .error(function () {
                    swal(
                        "おっとっと",
                        "サーバーに接続できませんでした。!",
                        "error"
                    );
                });
        }
    )
});

function refetch_data() {
    if($('#search-video').val() != '' || $('#video-subject-filter').val() !=''){
        search();
    } else{
    if (localStorage.getItem("current_video_page")) {
        page = localStorage.getItem("current_video_page");
        if (localStorage.getItem('delete1')) { /// tránh lỗi khi xóa record cuối cùng của phân trang
            page = page - 1;
        }
    } else page = 1;
    $.ajax({
        method: "get",
        url: "/admin/video/fetch?page=" + page,
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
}

function clear_form() {
    document.getElementById("form-add-question").reset();
    old_file = null;
    old_file2 = null;
    $("#video_preview").hide();
    $("#illustration_preview").show();
    $("#illustration_preview").attr("src", "/backend/images/icons/default.png");
    $("#question_media_name").text("");
    $("#thumbnail_preview").attr("src", "/backend/images/icons/default.png");
    $("#question_thumbnail_name").text("");
    $('#video-title-input').removeClass('video-title-error-border');
    $('#video-title-input').addClass('video-title-input');
    var video = document.getElementById("video_preview");
    video.pause();
    clear_media();
    clear_error();
}

function clear_media() {
    if (document.getElementById("illustration_preview")) {
        document
            .getElementById("illustration_preview")
            .classList.remove("fullable");
    }
    if (document.getElementById("video_preview")) {
        document.getElementById("video_preview").classList.remove("fullable");
    }
}

function clear_error() {
    $(".error-text").text("\xa0");
    localStorage.removeItem("media");
    localStorage.removeItem("thumbnail");
    $("#illustration_preview").removeClass("preview-video-error");
    $("#video_preview").removeClass("preview-video-error");
    $("#thumbnail_preview").removeClass("preview-video-error");
}

$(document).on("click", ".fullable", function () {
    this.requestFullscreen();
});

$(document).on("click", "#pagination a", function (event) {
    event.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    localStorage.setItem("current_video_page", page);
    refetch_data();
});

function vallidate() {
    let e = 0;
    $title = $('#video-title-input').val();
    if (!$title.trim()) {
        $('#question-error-title').text('必須フィールドです。');
        $('#video-title-input').removeClass('video-title-input');
        $('#video-title-input').addClass('video-title-error-border');
        e++
    } else {
        $('#video-title-input').removeClass('video-title-error-border');
        $('#video-title-input').addClass('video-title-input');
        $('#question-error-title').text('\xa0');
    }
    if (document.getElementById("question_media")) {
        $media = document.getElementById("question_media").files;
        if (localStorage.getItem("media")) {
            if (
                $media.length == 0 &&
                localStorage.getItem("media") == null &&
                old_file == null
            ) {
                $("#question-error-media").css("margin-top", "-241px");
                $("#question-error-media").text("必須フィールドです。");
                $("#illustration_preview").addClass("preview-video-error");
                e++;
            } else if (
                $media.length != 0 &&
                !["video/mp4"].includes($media[0].type)
            ) {
                $("#question-error-media").css("margin-top", "-266px");
                $("#question-error-media").text(
                    "MP4の動画ファイルをアップロードしてください。"
                );
                $("#video_preview").addClass("preview-video-error");
                e++;
            } else if (
                $media.length != 0 &&
                ["video/mp4"].includes($media[0].type)
            ) {
                var fsize = $media[0].size;
                if (Math.round(fsize / 1024 / 1024) > 150) {
                    $("#question-error-media").css("margin-top", "-266px");
                    $("#question-error-media").text(
                        "150MB以下の動画ファイルをアップロードしてください。"
                    );
                    $("#video_preview").addClass("preview-video-error");
                    e++;
                } else {
                    $("#question-error-media").text("\xa0");
                    $("#question-error-media").css("margin-top", "-241px");
                    $("#illustration_preview").removeClass(
                        "preview-video-error"
                    );
                    $("#video_preview").removeClass("preview-video-error");
                }
            }
        } else {
            if ($media.length == 0 && old_file == null) {
                $("#question-error-media").css("margin-top", "-241px");
                $("#question-error-media").text("必須フィールドです。");
                $("#illustration_preview").addClass("preview-video-error");
                e++;
            } else if (!["video/mp4"].includes($media[0].type)) {
                $("#question-error-media").css("margin-top", "-266px");
                $("#question-error-media").text(
                    "MP4の動画ファイルをアップロードしてください。"
                );
                $("#video_preview").addClass("preview-video-error");
                e++;
            } else {
                var fsize = $media[0].size;
                if (Math.round(fsize / 1024 / 1024) > 150) {
                    $("#question-error-media").css("margin-top", "-266px");
                    $("#question-error-media").text(
                        "150MB以下の動画ファイルをアップロードしてください。"
                    );
                    $("#video_preview").addClass("preview-video-error");
                    e++;
                } else {
                    $("#question-error-media").text("\xa0");
                    $("#question-error-media").css("margin-top", "-241px");
                    $("#illustration_preview").removeClass(
                        "preview-video-error"
                    );
                    $("#video_preview").removeClass("preview-video-error");
                }
            }
        }
    }
    $thumbnail = document.getElementById("video_thumbnail").files;
    if ($thumbnail.length != 0) {
        if (
            !["image/jpeg", "image/png", "image/jpg"].includes(
                $thumbnail[0].type
            )
        ) {
            $("#question-error-thumbnail").text("PNG、JPG、JPEGの画像ファイルをアップロードしてください。");
            $("#thumbnail_preview").addClass("preview-video-error");
            e++;
        } else {
            $("#question-error-thumbnail").text("\xa0");
            $("#thumbnail_preview").removeClass("preview-video-error");
        }
    }
    if (e > 0) {
        return false;
    }
}
