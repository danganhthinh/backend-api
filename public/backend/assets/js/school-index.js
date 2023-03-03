$(document).ready(function () {
    let keyRemove = [
        "current_mentor_page",
        "current_group_page",
        "current_user_page",
        "searchGroup",
        "searchMentor",
        "school_id",
        "group_id",
        "grade_id",
        "searchKey"
    ];
    for (key of keyRemove) {
        localStorage.removeItem(key);
    }
    if (localStorage.getItem("check-back-school")) {
        localStorage.removeItem("check-back-school");
        if (localStorage.getItem("searchSchool")) {
            $searchKey = localStorage.getItem("searchSchool");
            $("#searchSchool").val($searchKey);
            localStorage.setItem("current_school_page", 1)
        }
        if (localStorage.getItem("current_school_page")) {
            $page = localStorage.getItem("current_school_page");
            $("#hidden_page").val($page);
        } else {
            $("#hidden_page").val(1);
        }
        
    }
    ajaxsearch();
    $(document).on("click", "#edit-school", function (e) {
        e.preventDefault();
        localStorage.setItem("check-back-school", "1");
        history_back();
        var id = $(this).attr("data-id");
        window.location.href = "/admin/school/" + id + "/edit";
    });
    $(document).on("click", ".btn-add", function (e) {
        e.preventDefault();
        localStorage.setItem("check-back-school", "1");
        history_back();
        window.location.href = "/admin/school/create";
    });
    $("#btnSearchSchool").click(function (e) {
        e.preventDefault();
        ajaxsearch();
        // detail();
    });
});
