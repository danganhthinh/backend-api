function success_toast($text) {
    Toastify({
        close: true,
        text: '\xa0\xa0' + $text,
        duration: 3000,
        avatar: '/backend/images/icons/success.png',
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        offset: {
            x: 20,
            y: 50
        },
        style: {
            background: "white",
            border: "transparent",
            borderRadius: '15px',
            width: 'fit-content',
            height: 'fit-content',
            color: "black",
            paddingRight: '2.5%',
            paddingLeft: '1.8%',
            // paddingTop: '1%'
        },
        className: 'custom-toast',
        onClick: function () { }
    }).showToast();
}
function error_toast($text) {
    Toastify({
        close: true,
        text: '\xa0\xa0' + $text,
        duration: 3000,
        avatar: '/backend/images/icons/error.png',
        position: "right",
        gravity: "top",
        stopOnFocus: true,
        offset: {
            x: 20,
            y: 50
        },
        style: {
            background: "white",
            border: "transparent",
            borderRadius: '15px',
            width: 'fit-content',
            height: 'fit-content',
            color: "black",
            paddingRight: '2.5%',
            paddingLeft: '1.8%',
            // paddingTop: '1%'
        },
        className: 'custom-toast',
        onClick: function () { }
    }).showToast();
}

function excel_toast($text) {
    Toastify({
        close: true,
        text: '\xa0\xa0\xa0\xa0ファイルのインポートに失敗しました。\n \n' + $text,
        duration: -1,
        avatar: '/backend/images/icons/error.png',
        position: "right",
        gravity: "top",
        stopOnFocus: true,
        offset: {
            x: 20,
            y: 50
        },
        style: {
            background: "white",
            border: "transparent",
            borderRadius: '15px',
            width: 'fit-content',
            height: '80px',
            color: "black",
            paddingRight: '20px',
            paddingLeft: '20px',
            // paddingTop: '1%'
        },
        className: 'custom-toast',
        onClick: function () {
            window.onbeforeunload = null;
            window.location.href = $text;
            window.onbeforeunload = null;
        }
    }).showToast();
}