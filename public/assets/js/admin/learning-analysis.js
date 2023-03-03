
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

    if (localStorage.getItem('learning-school')) {
        $school_id = localStorage.getItem('learning-school');
        document.getElementById('learning-select-school').value = $school_id;
    }
    change_grades_by_school();
    if (localStorage.getItem('learning-school')) {
        $grade_id = localStorage.getItem('learning-grade');
        $year = localStorage.getItem('learning-year');
        $search = localStorage.getItem('learning-search');
        document.getElementById('learning-select-grade').value = $grade_id;
        document.getElementById('learning-select-school_year').value = $year;
        document.getElementById('learning-search').value = $search;
    } else {
        localStorage.removeItem('learning_page')
    }
    search_learning_analysis(false);
    localStorage.removeItem('learning-school')
});

$(document).on('click', '#learning_pagination a', function (event) {
    event.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    localStorage.setItem('learning_page', page);
    search_learning_analysis(false);
    // localStorage.removeItem('learning_page')
});

$(document).on('change', '#learning-select-school', function () {
    if (!$('#learning-select-grade').hasClass('learning-select-school-background')) {
        $('#learning-select-grade').toggleClass('learning-select-school-background');
        $('#learning-select-grade').attr('disabled', false)
    }
    let type = $('option:selected', this).attr('data-type');
    if (type == 'school') {
        change_grades_by_school();
    } else {
        if ($('#learning-select-grade').hasClass('learning-select-school-background')) {
            $('#learning-select-grade').toggleClass('learning-select-school-background');
            $('#learning-select-grade').attr('disabled', true)
        }
        $('#learning-select-grade').html('');
    }
})

// let current_year = new Date().getFullYear().toString();
$('#learning-select-school_year').datepicker({
    changeYear: true,
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
    autoclose: true,
    startDate: '2022',
    showButtonPanel: true
    // endDate: current_year
});


function change_grades_by_school() {
    let school_id = $('option:selected', '#learning-select-school').val();
    $.ajax({
        async: false,
        global: false,
        method: "get",
        url: "/admin/learning/grade/" + school_id,
        success: function (data) {
            $('#learning-select-grade').html('');
            let grades = data.data.grades;
            $('#learning-select-grade').append("<option value=''>全て</option>")
            grades.forEach(grade => {
                if (grade.name.length > 20) { grade.name = grade.name.substring(0, 20) + '...'; }
                $('#learning-select-grade').append("<option value=" + grade.id + ">" + grade.name + "</option>")
            });
        }
    })
}

$(document).on('change', '#learning-select-school', function () {
    localStorage.setItem('learning_page', 1);
    search_learning_analysis(true);
})

$(document).on('change', '#learning-select-grade', function () {
    localStorage.setItem('learning_page', 1);
    search_learning_analysis(true);
})

$(document).on('change', '#learning-select-school_year', function () {
    localStorage.setItem('learning_page', 1);
    search_learning_analysis(true);
})


$(document).on('click', '#btnSearch-learning', function () {
    localStorage.setItem('learning_page', 1);
    search_learning_analysis(true);
})

function search_learning_analysis($loading_status) {
    let page = '';
    if (localStorage.getItem('learning_page') != 1) {
        page = localStorage.getItem('learning_page');
    } else {
        page = 1;
    }
    let type = $('option:selected', '#learning-select-school').attr('data-type');
    let id = $('option:selected', '#learning-select-school').val();
    let group_id = null;
    let school_id = null;
    if (type == 'school') {
        school_id = id;
    } else {
        group_id = id;
    }
    let grade_id = $('#learning-select-grade').val();
    let school_year = $('#learning-select-school_year').val();
    let search = $('#learning-search').val();
    $.ajax({
        async: true,
        global: $loading_status,
        method: "get",
        data: {
            'school_id': school_id,
            'group_id': group_id,
            'grade_id': grade_id,
            'school_year': school_year,
            'search': search,
            'page': page
        },
        url: "/admin/learning/search",
        success: function (data) {
            $('#learning-data').html(data);
        }
    })
}

$(document).on('click', '#learning-arrow', function () {
    let account_id = $(this).attr('data-id');
    let year = $('#learning-select-school_year').val();
    let first_click = $(this).attr('first_click');
    // console.log(first_click);
    if (first_click == '1') {
        var reset = $('.learning-expands[data-id="' + account_id + '"]').html();
        localStorage.setItem('reset' + account_id, reset);
    }
    let tr = $('.learning-expands[data-id="' + account_id + '"]');
    if (tr.hasClass('hidden')) {
        $(this).attr('first_click', '0')
        $(this).html('&blacktriangledown;')
        $(this).css("color", "#07c5d3");
        $.ajax({
            async: false,
            global: false,
            method: "get",
            url: "/admin/learning/account/" + account_id + '/' + year,
            success: function (data) {
                let learnings = data.data.learnings.data;
                let extra = '';
                let clonedTr = '';
                learnings.forEach(learning => {
                    let subject_name = learning['subject_name'];
                    let level = learning['level'];
                    let number_training = learning['number_training'];
                    let number_correct_answers = learning['number_correct_answers'];
                    let video_number_learning = learning['video_number_learning'];
                    let correct_answer_video = learning['correct_answer_video'];
                    $('td[data-id="' + account_id + '"][data-name="subject_name"]').html(subject_name)
                    $('td[data-id="' + account_id + '"][data-name="level"]').html(level + ' 級')
                    $('td[data-id="' + account_id + '"][data-name="number_training"]').html(number_training + ' 回')
                    $('td[data-id="' + account_id + '"][data-name="number_correct_answers"]').html(number_correct_answers + ' 回')
                    $('td[data-id="' + account_id + '"][data-name="video_number_learning"]').html(video_number_learning + ' 回')
                    // $('td[data-id="' + account_id + '"][data-name="correct_answer_video"]').html(correct_answer_video + ' 回')
                    clonedTr = $('.learning-expands[data-id="' + account_id + '"]').html()
                    extra += clonedTr;
                });
                tr.removeClass('hidden');
                tr.html(extra);
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
    } else {
        $(this).css("color", "");
        $(this).html('&blacktriangleright;')
        reset = localStorage.getItem('reset' + account_id);
        tr.html(reset)
        tr.addClass('hidden');
    }
})

$(document).on('click', '.button-learning-detail', function () {
    let year = $('#learning-select-school_year').val();
    let account_id = $(this).attr('data-id');
    history_back();
    window.location.href = "/admin/learning/detail/" + account_id + '/' + year;
})

$(document).on('click', '.button-learning-compare', function () {
    let year = $('#learning-select-school_year').val();
    localStorage.setItem('learning-compare-year', year);
    let account_id = $(this).attr('data-id');
    localStorage.setItem('learning-compare-account_id', account_id);
    history_back();
    window.location.href = "learning/compare";
})

function history_back() {
    let grade_id = $('#learning-select-grade').val();
    let school_id = $('option:selected', '#learning-select-school').val();
    let search = $('#learning-search').val();
    let year = $('#learning-select-school_year').val();
    console.log(grade_id);
    localStorage.setItem('learning-school', school_id);
    localStorage.setItem('learning-grade', grade_id);
    localStorage.setItem('learning-year', year);
    localStorage.setItem('learning-search', search);
}