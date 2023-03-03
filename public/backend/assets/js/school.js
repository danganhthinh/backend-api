localStorage.removeItem("learning-school"); // liên quan đến màn thống kê
$(document).on("click", ".dropdown-details", function (event) {
    event.preventDefault();
    var id = $(this).attr("data-id");
    var icon = $(this);
    if ($("#details[data-id='" + id + "']").hasClass("hidden")) {
        $.ajax({
            type: "GET",
            url: "/admin/grade/grade-by-school/" + id,
            success: function (data) {
                $("#details[data-id='" + id + "']").html("");
                data.data.grade.forEach(function (item, index) {
                    // var bg = ;
                    $("#details[data-id='" + id + "']").append(`
                        <tr class="">
                            <td></td>
                            <td></td>
                            <td class="school-item"><p class="span-ellipsis" style="width: 150px">${item.name}</p></td>
                            <td class="school-item">${item.students}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    `);
                    $("#details[data-id='" + id + "']").removeClass("hidden");
                    icon.html("&blacktriangledown;").css({
                        color: "#8ddae7",
                        "vertical-align": "text-top",
                    });
                });
            },
        });
    } else {
        $("#details[data-id='" + id + "']").html("");
        $("#details[data-id='" + id + "']").addClass("hidden");
        icon.html("&blacktriangleright;").css({
            color: "#e1e1e1",
            "vertical-align": "baseline",
        });
    }
});

function fetch_data_school() {
    if (localStorage.getItem("current_school_page")) {
        var page = localStorage.getItem("current_school_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }
    var searchKey = $("#searchSchool").val();
    if (searchKey != "") {
        $.ajax({
            url: "/admin/school/search?page=" + page,
            data: {
                searchKey: searchKey,
                page: page,
            },
            success: function (data) {
                // console.log(data);
                $("#gird-school").html(data);
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/school/search?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                            page: pageNoRecord,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_school_page",
                                pageNoRecord
                            );
                            $("#gird-school").html(data);
                            detail();
                        },
                    });
                }
                // detail();
            },
        });
    } else {
        //     if ($(".table-responsive .table-body").length == 0) {
        $.ajax({
            global: false,
            url: "/admin/school/render?page=" + pageNoRecord,
            data: {
                searchKey: searchKey,
            },
            success: function (data) {
                localStorage.setItem("current_school_page", pageNoRecord);
                $("#gird-school").html(data);
                detail();
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/school/render?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_school_page",
                                pageNoRecord
                            );
                            $("#gird-school").html(data);
                            detail();
                        },
                    });
                }
            },
        });
    }
}
function ajaxsearch($loading) {
    var page = $("#hidden_page").val();
    var searchKey = $("#searchSchool").val();
    $.ajax({
        async: true,
        global: $loading,
        type: "get",
        url: "/admin/school/search?page=" + page,
        data: {
            searchKey: searchKey,
            page: page,
        },
        success: function (data) {
            $("#gird-school").html(data);
            // detail();
        },
        error: function (error) {
            console.log(error);
        },
    });
}

var search = debounce(function (e) {
    localStorage.setItem("current_school_page", 1);
    $value = $("#searchSchool").val();
    $page = $("#hidden_page").val();
    ajaxsearch();
}, 300);

// $("#searchSchool").keydown(search);
$(document).on("keydown", "#searchSchool", search);

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    $("#hidden_page").val(page);
    var searchKey = $("#searchSchool").val();
    localStorage.setItem("current_school_page", page);
    fetch_data_school();
});

function editSchool() {
    var i = $(".grade-code li").last().attr("data-id");
    var array = [];
    if (i !== undefined) {
        $("#edit-field-grade").click(function () {
            i++;
            $(".grade-name").append(
                `
            <li class="mb-2" data-id="` +
                    i +
                    `"><input type="text" class="input-school" name="grade[` +
                    i +
                    `][name]" maxlength="255"></li>
                <p class='text-danger error-text grade_` +
                    i +
                    `_name_error' data-id='` +
                    i +
                    `' style="margin: 20px 20px 10px 20px; height: 33px;"></p>
            `
            );
            $(".grade-code").append(
                `
            <li class="mb-2" data-id="` +
                    i +
                    `"><input type="text" class="input-school" name="grade[` +
                    i +
                    `][code]" value="" maxlength="3" class="gradeCode"><i class="danger fa fa-trash remove-grade-code" data-id2="` +
                    i +
                    `" aria-hidden="true" style="margin-left: 24px; cursor:pointer"></i></li>
                <p class="text-danger error-text grade_` +
                    i +
                    `_code_error" data-code="grade[` +
                    i +
                    `][code]" style="margin: 20px 20px 10px 20px; height: 33px; max-width: 256px"></p>
            `
            );
            if ($(".grade-code li").length == 1) {
                $(".label-code").html(
                    '<label for="" class="text-center mt-le-1">学科ID</label>'
                );
            }
        });
    } else {
        var i1 = 0;
        $("#edit-field-grade").click(function () {
            $(".label-code").html(
                '<label for="" class="text-center mt-le-1 label-school">学科ID</label>'
            );
            i1++;
            $(".grade-name").append(
                `
            <li class="mb-2" data-id="` +
                    i1 +
                    `"><input type="text" name="grade[` +
                    i1 +
                    `][name]" maxlength="255"></li>
                <p class='text-danger error-text grade_` +
                    i1 +
                    `_name_error' data-id='` +
                    i1 +
                    `' style="margin: 20px 20px 10px 20px; height: 33px;"></p>
            `
            );

            $(".grade-code").append(
                `
            <li class="mb-2" data-id="` +
                    i1 +
                    `"><input type="text" name="grade[` +
                    i1 +
                    `][code]" value="" maxlength="3" class="gradeCode"><i class="danger fa fa-trash remove-grade-code ml-2" data-id2="` +
                    i1 +
                    `" aria-hidden="true" style="cursor: pointer"></i></li>
                <p class="text-danger error-text grade_` +
                    i1 +
                    `_code_error" data-code="grade[` +
                    i1 +
                    `][code] style="margin: 20px 20px 10px 20px; height: 33px; max-width: 256px"></p>
            `
            );
        });
    }
    $(document).on("click", ".delete-grade", function () {
        // array.push(i);
        var id = $(this).attr("data-id");
        array.push(id);
        console.log(id);
        $("#array_delete_grade").val(array);
        console.log(array);
        $("li[data-id='" + id + "']").remove();
        $(".grade_" + id + "_name_error").remove();
        $(".grade_" + id + "_code_error").remove();
        if ($(".grade-code li").length == 0) {
            $(".label-code").html("");
        }
    });

    $(document).on("click", "#editSchool", function (e) {
        e.preventDefault();
        var id = $("#school-id").val();
        $(".error-text").html("");
        console.log($("#formEdit").serialize());
        $.ajax({
            url: "/admin/school/" + id,
            method: "PUT",
            processData: false,
            data: $("#formEdit").serialize(),
            success: function (data) {
                localStorage.setItem("item_success", "1");
                window.location.href = data.data.url;
            },
            error: function (xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                $.each(err.errors, function (key, value) {
                    console.log(key);
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    if (key.slice(0, 5) == "grade") {
                        $("p." + key.split(".").join("_") + "_error").html(
                            value[0]
                        );
                        $("#" + key.split(".").join("_")).css(
                            "border",
                            "1px solid red"
                        );
                        $("#" + key.split(".").join("_")).focus(function () {
                            $("#" + key.split(".").join("_")).css(
                                "border",
                                "none"
                            );
                            $("p." + key.split(".").join("_") + "_error").html(
                                ""
                            );
                        });
                    }
                    if ("#" + key == "#admin") {
                        $(".select2-selection").css("border", "1px solid red");
                    }
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                    $("#admin").change(function () {
                        var admin = $("#admin").val();
                        if (admin) {
                            $(".select2-selection").css("border", "none");
                            $(".admin_error").html("");
                        }
                    });
                });
            },
        });
    });
}

// function checkCode() {
//     $(document).on("change", ".gradeCode", function (e) {
//         const vals = $(".gradeCode")
//             .not(this)
//             .map(function () {
//                 return this.value;
//             })
//             .get();
//         const grade = this;
//         var that = $(this).attr("name");
//         if (vals.indexOf(this.value) != -1) {
//             if ($(".gradeCode[name='" + that + "']").val().length == 0) {
//                 // $(".gradeCode[name='" + that + "']").css(
//                 //     "border",
//                 //     "1px solid red"
//                 // );
//                 // $('.error-text[data-code="' + that + '"]').html("acb");
//                 $(".gradeCode[name='" + that + "']").focus(function () {
//                     $(".gradeCode[name='" + that + "']").css("border", "none");
//                     $('.error-text[data-code="' + that + '"]').html("");
//                 });
//             } else {
//                 $(".gradeCode[name='" + that + "']").css(
//                     "border",
//                     "1px solid red"
//                 );
//                 $('.error-text[data-code="' + that + '"]').html(
//                     "この学科IDは既に存在します。 別の学科IDを入力してください。"
//                 );
//                 grade.value = "";
//                 $(".gradeCode[name='" + that + "']").focus(function () {
//                     $(".gradeCode[name='" + that + "']").css("border", "none");
//                     $('.error-text[data-code="' + that + '"]').html("");
//                 });
//             }
//         }
//     });
// }

// $(document).on("click", "#add-field-grade", function (e) {
//     e.preventDefault();
//     addGrade();
// });

function addSchool() {
    var i = 0;
    $("#add-field-grade").click(function () {
        $(".label-code").html(
            '<label for="" class="text-center mt-le-1 label-school">学科ID</label>'
        );
        i++;
        $(".grade-name").append(
            `
            <li class="mb-2" data-id="` +
                i +
                `"><input type="text" class="input-school" name="grade[` +
                i +
                `][name]" maxlength="255"></li>
                <p class='text-danger error-text grade_` +
                i +
                `_name_error' data-id='` +
                i +
                `' style="margin: 10px 20px; height: 20px;"></p>
            `
        );

        $(".grade-code").append(
            `
            <li class="mb-2" data-id="` +
                i +
                `"><input type="text" class="input-school" name="grade[` +
                i +
                `][code]" value="" maxlength="3" class="gradeCode"><i class="danger fa fa-trash remove-grade-code ml-2" data-id2="` +
                i +
                `" aria-hidden="true" style="cursor: pointer"></i></li>
                <p class="text-danger error-text grade_` +
                i +
                `_code_error" data-code="grade[` +
                i +
                `][code]" style="margin: 10px 20px; height: 20px;"></p>
            `
        );
    });
    $(document).on("click", ".remove-grade-code", function (e) {
        e.preventDefault();
        $id2 = $(this).attr("data-id2");
        $("label[data-id='" + $id2 + "']").remove();
        $("li[data-id='" + $id2 + "']").remove();
        $(".grade_" + $id2 + "_name_error").remove();
        $(".grade_" + $id2 + "_code_error").remove();
        if ($(".grade-code li").length == 0) {
            $(".label-code").html("");
        }
        // console.log($('.grade-code li').length);
    });
    $("#addSchool").click(function (e) {
        e.preventDefault();
        $(".error-text").html("");
        $.ajax({
            url: "/admin/school",
            method: "POST",
            processData: false,
            data: $("#formAdd").serialize(),
            success: function (data) {
                // console.log(data.data.url);
                localStorage.setItem("item_success", "1");
                window.location.href = data.data.url;
            },
            error: function (xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                // console.log(err.errors);
                // if (err.errors) {
                $.each(err.errors, function (key, value) {
                    $("p." + key + "_error").html(value[0]);
                    $("#" + key).css("border", "1px solid red");
                    if (key.slice(0, 5) == "grade") {
                        $("p." + key.split(".").join("_") + "_error").html(
                            value[0]
                        );
                        $("#" + key.split(".").join("_")).css(
                            "border",
                            "1px solid red"
                        );
                        $("#" + key.split(".").join("_")).focus(function () {
                            $("#" + key.split(".").join("_")).css(
                                "border",
                                "none"
                            );
                            $("p." + key.split(".").join("_") + "_error").html(
                                ""
                            );
                        });
                    }
                    if ("#" + key == "#admin") {
                        $(".select2-selection").css("border", "1px solid red");
                    }
                    // $("#admin").select2({ containerCssClass : "border-0" });;
                    $("#" + key).focus(function () {
                        $("#" + key).css("border", "none");
                        $("p." + key + "_error").html("");
                    });
                    $("#admin").change(function () {
                        var admin = $("#admin").val();
                        if (admin) {
                            $(".select2-selection").css("border", "none");
                            $(".admin_error").html("");
                        }
                    });
                });
                // }
            },
        });
    });
}

$(document).on("click", ".delete-school", function () {
    var id = $(this).attr("data-id");
    swal(
        {
            title: "この学校を削除してもよろしいですか?",
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
                url: "/admin/school/" + id,
                dataType: "html",
                success: function (data) {
                    fetch_data_school();
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

function history_back() {
    var searchKey = $("#searchSchool").val();
    if (localStorage.getItem("current_school_page")) {
        var page = localStorage.getItem("current_school_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }

    localStorage.setItem("searchSchool", searchKey);
    localStorage.setItem("current_school_page", page);
}

$("#admin").select2({
    maximumSelectionLength: 10,
});

$(document).on("change", "#admin", function (e) {
    e.preventDefault();
    if ($(".select2-selection__rendered li").length == 1) {
        $(".select2-container .select2-selection--multiple").css(
            "height",
            "40px"
        );
    } else {
        $(".select2-container .select2-selection--multiple").css(
            "height",
            "max-content"
        );
    }
});
$(document).ready(function () {
    addSchool();
    editSchool();
});
