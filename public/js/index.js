$('.custom-file-input').on('change', function (event) {
    let inputFile = event.currentTarget;
    $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image_preview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$(".img-upload").change(function () {
    readURL(this);
});
