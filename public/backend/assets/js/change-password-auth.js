$(document).ready(function() {
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
    $("#change-password").click(function () {
        $(".error-text").html("");
        $.ajax({
            url: "/admin/user/change-password",
            method: "POST",
            data: $("#form-change-password").serialize(),
            success: function (data) {
                success_toast("成功しました");
                setTimeout(function() {
                    window.location.href = "/admin/logout"
                }, 2000);
            },
            error: function (xhr, status, error) {
                var err = JSON.parse(xhr.responseText);
                console.log(err);
                if (err.code == 400) {
                    $("#current_password").css("border", "1px solid red");
                    $("p.current_password_error").html(
                        "入力したパスワードに誤りがあります。"
                    );
                    $("#current_password").focus(function() {
                        $("#current_password").css("border", "none");
                        $("p.current_password_error").html("");
                    })
                }
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
})