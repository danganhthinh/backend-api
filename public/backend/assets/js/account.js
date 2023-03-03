localStorage.removeItem("learning-school"); // liên quan đến màn thống kê
// window.onbeforeunload = function () {
//     localStorage.removeItem("current_user_page");
// };
function success_toast($text) {
    Toastify({
        close: true,
        text: '\xa0\xa0' + $text,
        duration: 3000,
        avatar: '/backend/images/icons/success.png',
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        offset: {
            x: 20,
            y: 50
        },
        style: {
            background: "white",
            border: "transparent",
            borderRadius: '15px',
            width: 'fit-content',
            height: 'fit-content',
            color: "black",
            paddingRight: '2.5%',
            paddingLeft: '1.8%',
            // paddingTop: '1%'
        },
        className: 'custom-toast',
        onClick: function () { }
    }).showToast();
}

// function excel_toast($text) {
//     Toastify({
//         close: true,
//         text: "ファイルのインポートに失敗しました。\n" + $text,
//         duration: -1,
//         avatar: "/backend/images/icons/error.png",
//         position: "right",
//         gravity: "top",
//         stopOnFocus: true,
//         offset: {
//             x: 20,
//             y: 50,
//         },
//         style: {
//             background: "white",
//             border: "transparent",
//             borderRadius: "15px",
//             width: "fit-content",
//             height: "7%",
//             color: "black",
//             paddingRight: "5%",
//             paddingTop: "1%",
//         },
//         className: "custom-toast",
//         onClick: function () {
//             window.location.href = $text;
//         },
//     }).showToast();
// }

function showModalAddMultiple() {
    $("#addMultipleUser").click(function (e) {
        e.preventDefault();
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("modal-import-close")[0];
        modal.style.display = "block";
        span.onclick = function () {
            modal.style.display = "none";
            $(".user-import-name").html("");
            document.getElementById("user-import").value = "";
            $("#button-upload-ok")
                .removeClass("button-upload-ok-enabled")
                .attr("disabled", "disabled");
        };
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
                $(".user-import-name").html("");
                document.getElementById("user-import").value = "";
                $("#button-upload-ok")
                    .removeClass("button-upload-ok-enabled")
                    .attr("disabled", "disabled");
            }
        };
    });
}

function addMultipleUser() {
    showModalAddMultiple();
    $(document).on("click", "#button-upload-user", function () {
        document.getElementById("user-import").click();
    });

    $("#user-import").on("change", function (event) {
        let fileNameField = document.getElementById("user-import-name");
        let uploadFileName = event.target.files[0].name;
        fileNameField.textContent = uploadFileName;
        $("#button-upload-ok").prop("disabled", false);
        $("#button-upload-ok").addClass("button-upload-ok-enabled");
    });

    $(document).on("click", "#button-upload-ok", function (e) {
        $(this).attr("disabled", "disabled");
        e.preventDefault();
        formData = new FormData();
        var file = $("#user-import")[0].files[0];
        formData.append("file_excel", file);
        $(".import-error").html("");
        var fileNameField = document.getElementsByTagName("p")[0].innerHTML;
        console.log(fileNameField);
        if (
            /[^.]+$/.exec(fileNameField)[0] !== "xlsx" &&
            /[^.]+$/.exec(fileNameField)[0] !== "xls" &&
            /[^.]+$/.exec(fileNameField)[0] !== "csv"
        ) {
            $(".import-error").html("フ ァ イ ル の 形 式 が 無 効 で す。");
        } else {
            $.ajax({
                method: "post",
                url: "/admin/import-user-excel",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    success_toast(data.data);
                    $("#myModal").css("display", "none");
                    $(".user-import-name").html("");
                    document.getElementById("user-import").value = "";
                    $("#button-upload-ok")
                        .removeClass("button-upload-ok-enabled")
                        .attr("disabled", "disabled");
                    fetch_data_user();
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 400) {
                        excel_toast(JSON.parse(xhr.responseText).message);
                        $(".danger-import").attr(
                            "href",
                            JSON.parse(xhr.responseText).message
                        );
                        $("#myModal").css("display", "none");
                        $(".user-import-name").html("");
                        document.getElementById("user-import").value = "";
                        $("#button-upload-ok")
                            .removeClass("button-upload-ok-enabled")
                            .attr("disabled", "disabled");
                        // $(".danger-import").each(function () {
                        //     var $this = $(this);
                        //     setTimeout(function () {
                        //         window.location = $this.attr("href");
                        //     }, 2000);
                        // });
                    }
                    var err = JSON.parse(xhr.responseText);
                    if (err.errors) {
                        $.each(err.errors, function (key, value) {
                            error_toast(value);
                        });
                    }
                },
            });
        }
    });
}

$("#school-name").on("change", function (e) {
    e.preventDefault();
    get_grades();
});

function get_grades($loading) {
    var id = $("#school-name").val();
    var type = $("option:selected", "#school-name").attr("data-type");
    var group_id = null;
    var school_id = null;
    if (type == "school") {
        school_id = id;
    } else if (type == "group") {
        group_id = id;
    }
    $("#grade").html("");
    if (type == "school") {
        $.ajax({
            async: false,
            global: false,
            type: "get",
            url: "/admin/grade/grade-by-school/" + school_id,
            success: function (data) {
                if (data.data.grade == "") {
                    $("#grade").attr("disabled", "disabled");
                }
                $("#grade").append(`<option value=''></option`);
                data.data.grade.forEach(function (item, index) {
                    $("#grade").append(`
                                    <option value="${item.id}">${item.name}</option>
                                `);
                });
            },
        });
        $("#grade").prop("disabled", false);
        // $("#grade").select2();
    } else {
        $("#grade").prop("disabled", true);
    }
}

$(document).on("click", "#btnSearchAccount", function (e) {
    localStorage.setItem("current_user_page", 1);
    e.preventDefault();
    searchUser();
});

function searchUser($loading) {
    // $("#school-name").select2();
    var id = $("option:selected", "#school-name").val();
    var type = $("option:selected", "#school-name").attr("data-type");
    var group_id = null;
    var school_id = null;
    if (type == "school") {
        school_id = id;
    } else if (type == "group") {
        group_id = id;
    }
    var schoolYear = $("#school_year_id").val();
    var grade = $("#grade").val();
    var username = $("#username").val();
    var page = $("#hidden_page").val();
    // var page = localStorage.setItem("current_user_page", 1);
    // if(localStorage.getItem('current_user_page')) {
    //     var page = localStorage.getItem("current_user_page")
    // } else {
    //     var page = $("#hidden_page").val();
    // }
    $.ajax({
        async: true,
        global: $loading,
        type: "GET",
        url: "/admin/user/search",
        data: {
            school_id: school_id,
            group_id: group_id,
            schoolYear: schoolYear,
            grade_id: grade,
            searchKey: username,
            page: page,
        },
        success: function (data) {
            $("#gird-user").html(data);
        },
        error: function (error) {},
    });
}

function fetch_data_user($loading) {
    var id = $("option:selected", "#school-name").val();
    var type = $("option:selected", "#school-name").attr("data-type");
    var group_id = null;
    var school_id = null;
    if (type == "school") {
        school_id = id;
    } else if (type == "group") {
        group_id = id;
    }
    var schoolYear = $("#school_year_id").val();
    var grade = $("#grade").val();
    var searchKey = $("#username").val();
    if (localStorage.getItem("current_user_page") != 1) {
        var page = localStorage.getItem("current_user_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }
    $.ajax({
        global: false,
        url: "/admin/user/search",
        data: {
            school_id: school_id,
            group_id: group_id,
            schoolYear: schoolYear,
            grade_id: grade,
            searchKey: searchKey,
            page: page,
        },
        success: function (data) {
            if ($(".table-responsive .table-body").length == 0) {
                $.ajax({
                    global: false,
                    url: "/admin/user/search",
                    data: {
                        school_id: school_id,
                        group_id: group_id,
                        schoolYear: schoolYear,
                        grade_id: grade,
                        searchKey: searchKey,
                        page: pageNoRecord,
                    },
                    success: function (data) {
                        localStorage.setItem("current_user_page", pageNoRecord);
                        $("#gird-user").html(data);
                    },
                });
            } else {
                $("#gird-user").html(data);
            }
        },
    });
}

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    $("#hidden_page").val(page);
    var searchKey = $("#username").val();
    localStorage.setItem("current_user_page", page);
    fetch_data_user();
});

$(document).on("click", ".refresh-user", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-id");
    $.ajax({
        type: "get",
        url: "/admin/user/change-status/" + id,
        success: function (data) {
            fetch_data_user();
        },
    });
});

function deleteUser() {
    $(document).on("click", ".delete-user", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        swal(
            {
                title: "このユーザーを削除してもよろしいですか?",
                text: "削除の確認?",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "キャンセル",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "削除",
                closeOnConfirm: false,
            },
            function () {
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                // if (confirm("Are you sure to delete?") == true) {
                $.ajax({
                    type: "DELETE",
                    url: "/admin/user/" + id,
                    dataType: "html",
                    success: function (data) {
                        console.log(data.message);
                        fetch_data_user();
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
        );
    });
}
$(document).on("change", ".school-name", function (e) {
    e.preventDefault();
    getGradeBySchool();
});

function getGradeBySchool() {
    // var codeSchool = $('.school-name').val();
    // var codeGroup = $('.school-name').val();
    var codeSchool = $(".school-name")
        .find(":selected")
        .attr("data-school-code");
    var codeGroup = $(".school-name").find(":selected").attr("data-group-code");
    let type = $("option:selected", "#school").attr("data-type");
    let id = $("option:selected", "#school").val();
    let group_id = null;
    let school_id = null;
    if (type == "school") {
        school_id = id;
    } else if (type == "group") {
        group_id = id;
    } else if (type == undefined) {
        $("#school_code").attr("value", "");
        // $(".label-grade").html("")
    }

    $("#school_code").attr("value", codeGroup);
    $.ajax({
        type: "get",
        url: "/admin/grade/grade-by-school/" + school_id,
        success: function (data) {
            // if(data.data.grade)

            $("#grade_id").empty();
            if (school_id == undefined || data.data.grade.length == 0) {
                $("#grade_id")
                    .attr("disabled", true)
                    .addClass("bg-transparent")
                    .css("background-image", "none")
                    .html("");
                $("#grade_code")
                    .attr("readonly", true)
                    .val("")
                    .addClass("bg-transparent")
                    .html("");
                $("#school_code").attr("value", codeSchool);
                $(".grade").html("");
            } else {
                console.log(data);
                // $(".school_code").val($(this).val());
                // $(".label-grade").append(` <label for="" class="mt-le-1 grade">学科ID</label>`)
                $(".grade").html("学科ID");
                $("#grade_id")
                    .attr("disabled", false)
                    .removeClass("bg-transparent")
                    .removeAttr("style");

                $("#school_code").attr("value", codeSchool);
                $("#grade_code").val("");
            }
            $("#grade_id").append(`<option value=''></option>`);

            data.data.grade.forEach(function (item, index) {
                if (item.name.length >= 28) {
                    $("#grade_id").append(
                        `
                            <option value="${item.id}" data-code="${
                            item.code
                        }">${item.name.substring(0, 28)}` +
                            `...</option>
                        `
                    );
                } else {
                    $("#grade_id").append(`
                            <option value="${item.id}" data-code="${item.code}">${item.name}</option>
                        `);
                }
                $(document).on("change", "#grade_id", function (e) {
                    var codeGrade = $(this).find(":selected").attr("data-code");
                    if (codeGrade == undefined) {
                        $("#grade_code").attr("value", "");
                    }
                    $("#grade_code")
                        .attr({
                            value: codeGrade,
                            readonly: "readonly",
                        })
                        .addClass("bg-transparent");
                    $("#grade_code").val(codeGrade);
                });
            });
        },
    });

    // }
}

// function addUser() {
// getGradeBySchool();
$(document).on("click", "#addUser", function (e) {
    e.preventDefault();
    var codeSchool = null;
    var codeGroup = null;
    let type = $("option:selected", "#school").attr("data-type");
    let id = $("option:selected", "#school").val();
    let code = $(".code").val();
    let group_id = null;
    let school_id = null;
    let grade_id = $("#grade").val();
    let grade_code = $("#grade_code").val();
    var data = $("#formAdd").serializeArray();

    if (type == "school") {
        school_id = id;
        codeSchool = code;
        data.push({ name: "school_id", value: school_id });
        data.push({ name: "codeSchool", value: code });
        // data.push({ name: "grade_id", value: grade_id });
        // data.push({ name: "grade_code", value: grade_code });
    } else {
        group_id = id;
        codeGroup = code;
        data.push({ name: "group_id", value: group_id });
        // data.push({ name: "codeGroup", value: code });
    }
    let full_name = $("#full_name").val();
    let birthday = $("#birthday").val();
    let student_code = $("#student_code").val();
    let expired_at = $("#expired_at").val();
    $(".error-text").html("");
    $.ajax({
        url: "/admin/user",
        method: "POST",
        data: data,
        success: function (data) {
            // if($(".error-text").text())
            // if($(".school_error").html())
            localStorage.setItem("item_success", "1");
            window.location.href = data.data.url;
        },
        error: function (xhr, status, error) {
            // console.log(school_id);
            if (type == undefined) {
                $(".school_error").html("必須フィールドです。");
                $(".school-name").css("border", "1px solid red");
                $(".school-name").change(function () {
                    var school = $(".school-name").val();
                    if (school) {
                        $(".school-name").css("border", "none");
                        $(".school_error").html("");
                    }
                });
                var err = JSON.parse(xhr.responseText);
                $.each(err.errors, function (key, value) {
                    console.log(key);
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                });
            } else if ($("#grade option").length == 1 && type == "school") {
                $(".grade_error").html("この学校には成績がありません");
                var err = JSON.parse(xhr.responseText);
                $.each(err.errors, function (key, value) {
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                });
            } else if (
                $("#grade option").length > 1 &&
                grade_id == "" &&
                type == "school"
            ) {
                $(".grade_error").html("必須フィールドです。");
                $("#grade").css("border", "1px solid red");
                if (school) {
                    $(".school-name").css("border", "none");
                    $(".school_error").html("");
                }
                var err = JSON.parse(xhr.responseText);
                $.each(err.errors, function (key, value) {
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                    $("#grade").focus(function () {
                        $("#grade").css("border", "none");
                        $("p.grade_error").html("");
                    });
                });
            } else {
                var err = JSON.parse(xhr.responseText);
                $.each(err.errors, function (key, value) {
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                });
            }
        },
    });
});
// }

function history_back() {
    var type_id = $("option:selected", "#school-name").val();
    var type = $("option:selected", "#school-name").attr("data-type");
    var group_id = "";
    var school_id = "";
    if (type == "school") {
        school_id = type_id;
    } else if (type == "group") {
        group_id = type_id;
    }
    var schoolYear = $("#school_year_id").val();
    var grade = $("#grade").val();
    var searchKey = $("#username").val();
    if (localStorage.getItem("current_user_page") != 1) {
        var page = localStorage.getItem("current_user_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }

    localStorage.setItem("group_id", group_id);
    localStorage.setItem("grade_id", grade);
    localStorage.setItem("schoolYear", schoolYear);
    localStorage.setItem("school_id", school_id);
    localStorage.setItem("searchKey", searchKey);
    // window.location.href = "/admin/user";
    localStorage.setItem("current_user_page", page);
}

$("#editUser").click(function (e) {
    e.preventDefault();
    var id = $("#user-id").val();
    $(".error-text").html("");
    $.ajax({
        url: "/admin/user/" + id,
        method: "PUT",
        processData: false,
        data: $("#formEdit").serialize(),
        success: function (data) {
            // console.log(data);
            localStorage.setItem("item_success", "1");
            window.location.href = "/admin/user";
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            // console.log(err.errors);
            $.each(err.errors, function (key, value) {
                $("p." + key + "_error").html(value[0]);
                $("#" + key).css("border", "1px solid red");
                $("#" + key).focus(function () {
                    $("#" + key).css("border", "none");
                    $("p." + key + "_error").html("");
                });
            });
            // }
        },
    });
});

$("#modal-change-password").click(function (e) {
    e.preventDefault();
    var modal = document.getElementById("modalChangePassword");
    var span = document.getElementsByClassName("modal-import-close")[0];
    var id = $("#form-change-password-user").attr("data-id");
    modal.style.display = "block";
    span.onclick = function () {
        modal.style.display = "none";
        $("#current_password").val("");
        $("#password").val("");
        $("#confirm_password").val("");
        // document.getElementById("user-import").value = "";
        // $("#button-upload-ok")
        //     .removeClass("button-upload-ok-enabled")
        //     .attr("disabled", "disabled");
    };
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $("#current_password").val("");
            $("#password").val("");
            $("#confirm_password").val("");
            // $(".user-import-name").html("");
            // document.getElementById("user-import").value = "";
            // $("#button-upload-ok")
            //     .removeClass("button-upload-ok-enabled")
            //     .attr("disabled", "disabled");
        }
    };
    $(document).on("click", ".toggle-password-current-password", function () {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $("#current_password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).removeClass("fa-eye-slash").css("color", "#c1c1c1");
            $(this).addClass("fa-eye").css("color", "#000000");
        } else if (input.attr("type") === "text") {
            input.attr("type", "password");
            $(this).removeClass("fa-eye").css("color", "#000000");
            $(this).addClass("fa-eye-slash").css("color", "#c1c1c1");
        }
    });
    $(document).on("click", ".toggle-password", function () {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).removeClass("fa-eye-slash").css("color", "#c1c1c1");
            $(this).addClass("fa-eye").css("color", "#000000");
        } else if (input.attr("type") === "text") {
            input.attr("type", "password");
            $(this).removeClass("fa-eye").css("color", "#000000");
            $(this).addClass("fa-eye-slash").css("color", "#c1c1c1");
        }
    });
    $(document).on("click", ".toggle-password-confirm-password", function () {
        // alert($('.password').attr({ type: "password" , 'data-id': i}))
        var input = $("#confirm_password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            $(this).removeClass("fa-eye-slash").css("color", "#c1c1c1");
            $(this).addClass("fa-eye").css("color", "#000000");
        } else if (input.attr("type") === "text") {
            input.attr("type", "password");
            $(this).removeClass("fa-eye").css("color", "#000000");
            $(this).addClass("fa-eye-slash").css("color", "#c1c1c1");
        }
    });
});
// function changePasswordUser() {
$("#change-password-user").click(function (e) {
    e.preventDefault();
    $(".error-text").html("");
    var modal = document.getElementById("modalChangePassword");
    var id = $("#form-change-password-user").attr("data-id");
    $.ajax({
        url: "/admin/user/change-student-password/" + id,
        method: "POST",
        data: $("#form-change-password-user").serialize(),
        success: function (data) {
            success_toast("成功しました");
            modal.style.display = "none";
            $("#current_password").val("");
            $("#password").val("");
            $("#confirm_password").val("");
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            if (err.errors) {
                // console.log(err.errors);
                $.each(err.errors, function (key, value) {
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                });
            }
        },
    });
});
// }
// $(document).on("click", ".back-user", function (e) {
//     e.preventDefault();
//     window.location.href = "/admin/user";
// });
