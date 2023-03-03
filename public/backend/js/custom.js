function previewImage(input, idImage, classInput = false) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(idImage).removeClass('hidden').attr('src', e.target.result);
            if (classInput) {
                $(classInput).val(e.target.result);
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
}
function ajaxDeleteObject(nameObject = 'Đối Tượng', url, callback) {
    nameObjectUpperCase = nameObject;
    swal({
        title: "Xóa " + nameObjectUpperCase,
        text: "Bạn Muốn Xóa " + nameObjectUpperCase + " Này?",
        icon: "warning",
        buttons: {
            cancel: {
                text: "Không",
                value: null,
                visible: true,
                className: "",
                closeModal: true,
            },
            confirm: {
                text: "Có",
                value: true,
                visible: true,
                className: "bg-danger",
                closeModal: false
            }
        }
    })
        .then((isConfirm) => {
            if (isConfirm) {
                $.ajax({
                    method: 'delete',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res == 1) {
                            callback();
                            swal({
                                title: "Đã Xóa!",
                                text: nameObjectUpperCase + " Đã Được Xóa",
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        text: "OK",
                                        className: "btn-primary",
                                        closeModal: true
                                    }
                                }
                            })
                        } else if (res == 0) {
                            swal("Lỗi!", nameObject.charAt(0).toUpperCase() + nameObject.substr(1).toLowerCase() + " không được xóa", "error");                             
                        } else {
                            swal("Lỗi!", nameObjectUpperCase + " Chưa Được Xóa", "error");
                        }
                    },
                    error: function () {
                        swal("Lỗi!", nameObjectUpperCase + " Chưa Được Xóa", "error");
                    }
                })
            }
        });
}

$('body .required.has-error input').on('keyup', function (e) {
    $(this).parents('.required.has-error').find('span.help-block').remove();
})

$('form input').bind("keypress", function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});
$(document).ready(function () {
    try {
        $.fn.datetimepicker.defaults.locale = 'vi';
    } catch (error) {
    }
    try {
        moment.locale('vi');
    } catch (error) {
    }
    setTimeout(() => {
        $('.alert-noti').alert('close')
    }, 5000);
});

function goToDivById(id, top = 0, time = 500) {
    $('html, body').animate({
        scrollTop: $("#" + id).offset().top - top
    }, time)
}

function factoryKey(length) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}


