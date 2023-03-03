//upload nhiều ảnh
$(document).ready(function () {
    document.getElementById('pro-image').addEventListener('change', readImage, true);
    
    $(".preview-images-zone").sortable();

    $(document).on('click', '.image-cancel', function () {
        let no = $(this).data('no');
        $(".preview-image.preview-show-" + no).remove();
        let count = $(".preview-images-zone input.count-image").val();
        count = Number(count) - 1;
        $(".preview-images-zone input.count-image").val(count);
        let totalCount = $(".preview-images-zone input.count-image").val();
        if (totalCount == 0) {
            if ($('#submit-img-pay')) {
                $('#submit-img-pay .btn-success').prop('disabled', true);
            }
        }
    });
    
});


var num = 10;

//upload nhiều ảnh
function readImage() {
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        var output = $(".preview-images-zone");
        for (let i = 0; i < files.length; i++) {
            var file = files[i];
            if (!file.type.match('image')) continue;

            var picReader = new FileReader();

            picReader.addEventListener('load', function (event) {
                var picFile = event.target;
                var html = '<div class="preview-image preview-show-' + num + '">' +
                    '<div class="image-cancel" data-no="' + num + '">x</div>' +
                    '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
                    '</div>';
                output.append(html);
                num = num + 1;
                let count = $(".preview-images-zone input.count-image").val();
                count = Number(count) + 1;
                $(".preview-images-zone input.count-image").val(count);
                if ($('#submit-img-pay')) {
                    $('#submit-img-pay .btn-success').prop('disabled', false);
                }
            });
            
            picReader.readAsDataURL(file);
        }
    } else {
        console.log('Browser not support');
    }
}
