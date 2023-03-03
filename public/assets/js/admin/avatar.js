localStorage.removeItem('learning-school')      // liên quan đến màn thống kê
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
let old_file;

$(document).on("change", ".button-upload-avatar", function (event) {
    let id = $(this).attr('data-id');
    let inputFile = document.getElementById('button-upload-avatar-' + id);
    let fileNameField = document.getElementById('file-' + id + '-name');
    let uploadFileName = event.target.files[0].name;
    fileNameField.textContent = uploadFileName;
    if (inputFile.files) {
        let [file] = inputFile.files;
        old_file = inputFile.files[0];
        document.getElementById(id + '_preview').src = URL.createObjectURL(file)
        $(".avatar-cancel-save[data-id=" + id + "]").css('visibility', 'visible');
    }
})

$(document).on('click', '.fullable', function () {
    this.requestFullscreen()
});

$(document).on("click", ".button-cancel-avatar", function () {
    let id = $(this).attr('data-id');
    $(".avatar-cancel-save[data-id=" + id + "]").css('visibility', 'hidden');
    let fileNameField = document.getElementById('file-' + id + '-name');
    let file_name = $(".avatar-image-name[data-id=" + id + "]").val();
    fileNameField.textContent = file_name;
    document.getElementById(id + '_preview').src = "/storage/" + file_name
})

$(document).on("click", ".button-save-avatar", function () {
    let id = $(this).attr('data-id');
    let type = $(this).attr('data-type');
    let formData = new FormData()
    if ($('#button-upload-avatar-' + id)[0].files[0]) {  //kiểm tra input file đó có file không (trường hợp chọn lại file rồi ấn cancel)
        var file = $('#button-upload-avatar-' + id)[0].files[0];
    } else {
        var file = old_file;
    }
    formData.append('id', id);
    formData.append('avatar', file);
    formData.append('type', type);
    formData.append('status', '1');
    // console.log(formData)
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
    $.ajax({
        method: "post",
        url: "/admin/illustration/update",
        processData: false,
        contentType: false,
        dataType: "json",
        data: formData,
        success: function (data) {
            success_toast(data.data);
            let old_file_name=data.message.split('/').reverse()[0];
            $(".avatar-image-name[data-id=" + id + "]").val(old_file_name)
            $(".avatar-cancel-save[data-id=" + id + "]").css('visibility', 'hidden');
            // refetch_data();
            old_file = null;
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
})

function refetch_data() {
    $.ajax({
        async:false,
        method: "get",
        url: "/admin/illustration/fetch",
        success: function (data) {
            $('#avatar-grid').html(data)
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
}