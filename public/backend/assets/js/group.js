localStorage.removeItem("learning-school"); // liên quan đến màn thống kê
function fetch_data_group() {
    if (localStorage.getItem("current_group_page") != 1) {
        var page = localStorage.getItem("current_group_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }
    var searchKey = $("#searchGroup").val();
    if (searchKey != "") {
        $.ajax({
            url: "/admin/group/search?page=" + page,
            data: {
                searchKey: searchKey,
                page: page,
            },
            success: function (data) {
                // console.log(data);
                $("#gird-group").html(data);
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/group/render?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                            page: pageNoRecord,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_group_page",
                                pageNoRecord
                            );
                            $("#gird-group").html(data);
                        },
                    });
                }
            },
        });
    } else {
        $.ajax({
            url: "/admin/group/render?page=" + page,
            data: {
                searchKey: searchKey,
                page: page,
            },
            success: function (data) {
                // console.log(data);
                $("#gird-group").html(data);
                if ($(".table-responsive .table-body").length == 0) {
                    $.ajax({
                        global: false,
                        url: "/admin/group/render?page=" + pageNoRecord,
                        data: {
                            searchKey: searchKey,
                            page: pageNoRecord,
                        },
                        success: function (data) {
                            localStorage.setItem(
                                "current_group_page",
                                pageNoRecord
                            );
                            $("#gird-group").html(data);
                        },
                    });
                }
            },
        });
    }
}

function ajaxsearch($loading) {
    var page = $("#hidden_page").val();
    var searchKey = $("#searchGroup").val();
    $.ajax({
        async: true,
        global: $loading,
        type: "get",
        url: "/admin/group/search?page=" + page,
        data: {
            searchKey: searchKey,
            page: page,
        },
        success: function (data) {
            $("#gird-group").html(data);
        },
        error: function (error) {
            console.log(error);
        },
    });
}

var search = debounce(function (e) {
    localStorage.setItem("current_group_page", 1);
    $value = $("#searchGroup").val();
    $page = $("#hidden_page").val();
    ajaxsearch();
}, 300);

$(document).on("keydown", "#searchGroup", search);

$(document).on("click", "#btnSearchGroup", function (e) {
    e.preventDefault();
    ajaxsearch();
});

$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    $("#hidden_page").val(page);
    $(this).parent().addClass("active");
    localStorage.setItem("current_group_page", page);
    fetch_data_group();
});

function history_back() {
    var searchKey = $("#searchGroup").val();
    if (localStorage.getItem("current_group_page")) {
        var page = localStorage.getItem("current_group_page");
        var pageNoRecord = page - 1;
    } else {
        var page = $("#hidden_page").val();
    }

    localStorage.setItem("searchGroup", searchKey);
    localStorage.setItem("current_group_page", page);
}

$("#admin").select2();

$(document).on("click", "#addGroup", function (e) {
    $(".error-text").html("");
    e.preventDefault();
    $.ajax({
        url: "/admin/group",
        method: "POST",
        processData: false,
        data: $("#formAdd").serialize(),
        success: function (data) {
            // console.log(data);
            localStorage.setItem("item_success", "1");
            window.location.href = data.data.url;
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            console.log(err.errors);
            // if (err.errors) {
            $.each(err.errors, function (key, value) {
                $("p." + key + "_error").html(value[0]);
                $("#" + key).css("border", "1px solid red");
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
            // }
        },
    });
});

$(document).on("click", "#editGroup", function (e) {
    var id = $("#group-id").val();
    $(".error-text").html("");
    e.preventDefault();
    $.ajax({
        url: "/admin/group/" + id,
        method: "PUT",
        processData: false,
        data: $("#formEdit").serialize(),
        success: function (data) {
            // console.log(data);
            localStorage.setItem("item_success", "1");
            window.location.href = data.data.url;
        },
        error: function (xhr, status, error) {
            var err = JSON.parse(xhr.responseText);
            $.each(err.errors, function (key, value) {
                $("p." + key + "_error").html(value[0]);
                $("#" + key).css("border", "1px solid red");
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
            // }
        },
    });
});

$(document).on("click", ".delete-group", function (e) {
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
            // $.ajaxSetup({
            //     headers: {
            //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
            //             "content"
            //         ),
            //     },
            // });
            // if (confirm("Are you sure to delete?") == true) {
            $.ajax({
                type: "DELETE",
                url: "/admin/group/" + id,
                dataType: "html",
                success: function (data) {
                    fetch_data_group();
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
