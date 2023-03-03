$(document).ready(function () {
    // if (localStorage.getItem("check-back")) {
    //     $("#gird-user").html("");
    // }
    let keyRemove = [
        "current_mentor_page",
        "current_school_page",
        "current_group_page",
        "searchSchool",
        "searchGroup",
        "searchMentor",
    ];
    for (key of keyRemove) {
        localStorage.removeItem(key);
    }
    if (localStorage.getItem("check-back")) {
        localStorage.removeItem("check-back");
        if (localStorage.getItem("school_id")) {
            $school_id = localStorage.getItem("school_id");
            // document.getElementById("school-name").value = $school_id;
            $("#school-name").val($school_id)
            get_grades();
        }
        if (localStorage.getItem("group_id")) {
            $group_id = localStorage.getItem("group_id");
            document.getElementById("school-name").value = $group_id;
        }
        if (localStorage.getItem("grade_id")) {
            console.log(localStorage.getItem("grade_id"));
            $grade_id = localStorage.getItem("grade_id");
            document.getElementById("grade").value = $grade_id;
        }
        if (localStorage.getItem("schoolYear")) {
            $schoolYear = localStorage.getItem("schoolYear");
            $("#school_year_id").val($schoolYear);
        }
        if (localStorage.getItem("searchKey")) {
            $searchKey = localStorage.getItem("searchKey");
            $("#username").val($searchKey);
        }
        if (localStorage.getItem("current_user_page")) {
            $page = localStorage.getItem("current_user_page");
            // $('#username').val($page);
            $("#hidden_page").val($page);
        } else {
            $("#hidden_page").val(1);
        }
        // searchUser();
    } 
    searchUser();
    // refreshUser();
    deleteUser();
    // addUser();
    addMultipleUser();
    changePasswordUser();
});

$(document).on("click", "#edit-user", function (event) {
    event.preventDefault();
    localStorage.setItem("check-back", "1");
    history_back();
    var id = $(this).attr("data-id");
    window.location.href = "/admin/user/" + id + "/edit";
});

$(document).on("click", "#btnAddSingle", function (e) {
    e.preventDefault();
    localStorage.setItem("check-back", "1");
    history_back();
    window.location.href = "/admin/user/create";
});
