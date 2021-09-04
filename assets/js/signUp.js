$('#password_eye').click(function () {
    let var_eye = $(this).find("i");
    if (var_eye.hasClass("bi-eye-fill")) {
        $('#password_field').attr({
            type: "text"
        });
        var_eye.removeClass("bi-eye-fill");
        var_eye.addClass("bi-eye-slash-fill");
    } else {
        $('#password_field').attr({
            type: "password"
        });
        var_eye.removeClass("bi-eye-slash-fill");
        var_eye.addClass("bi-eye-fill");
    }
});
$('#password_eye_repeat').click(function () {
    let var_eye = $(this).find("i");
    if (var_eye.hasClass("bi-eye-fill")) {
        $('#password_field_repeat').attr({
            type: "text"
        });
        var_eye.removeClass("bi-eye-fill");
        var_eye.addClass("bi-eye-slash-fill");
    } else {
        $('#password_field_repeat').attr({
            type: "password"
        });
        var_eye.removeClass("bi-eye-slash-fill");
        var_eye.addClass("bi-eye-fill");
    }
});