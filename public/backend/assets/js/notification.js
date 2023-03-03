function getGrade() {
    // $("#grade").empty();

    $("#school").on("change", function (e) {
        e.preventDefault();
        var id = $(this).find(":selected").val();
        var type = $("option:selected", this).attr("data-type");
        var group_id = null;
        var school_id = null;
        if (type == "school") {
            school_id = id;
        } else if (type == "group") {
            group_id = id;
        }
        var grade = $("#grade").val();
        var title = $("#title").val();
        var message = $("#message").val();
        $("#grade").html("");
        if (type == "school") {
            // $("#grade").html("");
            $.ajax({
                type: "get",
                url: "/admin/grade/grade-by-school/" + school_id,
                success: function (data) {
                    if (data.data.grade.length == 0) {
                        $("#grade")
                            .attr("disabled", true)
                            .addClass("bg-transparent")
                            .css("background-image", "none")
                            .html("");
                    } else if (data.data.grade.length != 0) {
                        $("#grade").append(`<option value=''>全て</option`);
                        data.data.grade.forEach(function (item, index) {
                            if (item.name.length >= 28) {
                                $("#grade").append(
                                    `
                                    <option value="${item.id}" data-code="${
                                        item.code
                                    }">${item.name.substring(0, 28)}` +
                                        `...</option>
                                `
                                );
                            } else {
                                $("#grade").append(`
                                    <option value="${item.id}" data-code="${item.code}">${item.name}</option>
                                `);
                            }
                        });
                        $("#grade")
                            .attr("disabled", false)
                            .removeClass("bg-transparent")
                            .removeAttr("style");
                    }
                },
            });
            // $("#grade").select2();
        } else {
            $("#grade")
                .attr("disabled", true)
                .addClass("bg-transparent")
                .css("background-image", "none")
                .html("");
        }
    });
    $("#push-noti").click(function () {
        var id = $("option:selected", "#school").val();
        var type = $("option:selected", "#school").attr("data-type");
        var group_id = null;
        var school_id = null;
        if (type == "school") {
            school_id = id;
        } else if (type == "group") {
            group_id = id;
        }
        var grade = $("#grade").val();
        var title = $("#title").val();
        var message = $("#message").val();
        $.ajax({
            url: "/admin/notification/send-notification",
            method: "POST",
            data: {
                school_id: school_id,
                group_id: group_id,
                grade_id: grade,
                title: title,
                message: message,
            },
            success: function (data) {
                success_toast("成功しました");
                document.getElementById("formSendNotification").reset();
                $("#grade")
                    .attr("disabled", true)
                    .addClass("bg-transparent")
                    .css("background-image", "none")
                    .html("");
            },
            error: function (xhr, status, error) {
                if (grade == null && type == "school") {
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
}

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
    getGrade();
});
