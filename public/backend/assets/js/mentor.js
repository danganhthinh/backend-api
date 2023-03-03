localStorage.removeItem("learning-school"); // liên quan đến màn thống kê

function fetch_data_mentor() {
    if (localStorage.getItem("current_mentor_page") != 1) {
        var page = localStorage.getItem("current_mentor_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }
    var searchKey = $("#searchMentor").val();
    if (searchKey != "") {
        $.ajax({
            url: "/admin/mentor/search?page=" + page,
            data: {
                searchKey: searchKey,
                page: page,
            },
            success: function (data) {
                // console.log(data);
                $("#gird-mentor").html(data);
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/mentor/search?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                            page: pageNoRecord,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_mentor_page",
                                pageNoRecord
                            );
                            $("#gird-mentor").html(data);
                        },
                    });
                }
            },
        });
    } else {
        $.ajax({
            url: "/admin/mentor/render?page=" + page,
            data: {
                searchKey: searchKey,
                page: page,
            },
            success: function (data) {
                // console.log(data);
                $("#gird-mentor").html(data);
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/mentor/render?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                            page: pageNoRecord,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_mentor_page",
                                pageNoRecord
                            );
                            $("#gird-mentor").html(data);
                        },
                    });
                }
            },
        });
    }
}

function ajaxsearch($loading) {
    var page = $("#hidden_page").val();
    var searchKey = $("#searchMentor").val();
    $.ajax({
        async: true,
        global: $loading,
        type: "get",
        url: "/admin/mentor/search?page=" + page,
        data: {
            searchKey: searchKey,
            page: page,
        },
        success: function (data) {
            // localStorage.setItem("current_mentor_page", 1)
            $("#gird-mentor").html(data);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

var search = debounce(function (e) {
    localStorage.setItem("current_mentor_page", 1);
    $value = $("#searchMentor").val();
    $page = $("#hidden_page").val();
    ajaxsearch();
}, 300);

$(document).on("keydown", "#searchMentor", search);

$(document).on("click", "#btnSearchMentor", function (e) {
    e.preventDefault();
    ajaxsearch();
});

function history_back() {
    var searchKey = $("#searchMentor").val();
    if (localStorage.getItem("current_mentor_page")) {
        var page = localStorage.getItem("current_mentor_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }

    localStorage.setItem("searchMentor", searchKey);
    localStorage.setItem("current_mentor_page", page);
}

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    $("#hidden_page").val(page);
    var searchKey = $("#searchMentor").val();
    // $('li').removeClass('active');
    localStorage.setItem("current_mentor_page", page);
    $(this).parent().addClass("active");
    fetch_data_mentor();
});

$(document).on("click", "#addMentor", function (e) {
    $(".error-text").html("");
    e.preventDefault();
    $.ajax({
        url: "/admin/mentor",
        method: "POST",
        processData: false,
        data: $("#formAdd").serialize(),
        success: function (data) {
            localStorage.setItem("item_success", "1");
            window.location.href = "/admin/mentor";
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);

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

$(document).on("click", "#editMentor", function (e) {
    var id = $("#mentor-id").val();
    $(".error-text").html("");
    e.preventDefault();
    $.ajax({
        url: "/admin/mentor/" + id,
        method: "PUT",
        processData: false,
        data: $("#formEdit").serialize(),
        success: function (data) {
            localStorage.setItem("item_success", "1");
            window.location.href = "/admin/mentor";
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
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

$(document).on("click", ".delete-mentor", function (e) {
    var id = $(this).attr("data-id");
    e.preventDefault();
    swal(
        {
            title: "このグループを削除してもよろしいですか?",
            text: "削除の確認?",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "キャンセル",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "削除",
            closeOnConfirm: false,
        },
        function () {
            $.ajax({
                type: "DELETE",
                url: "/admin/mentor/" + id,
                dataType: "html",
                success: function (data) {
                    fetch_data_mentor();
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
