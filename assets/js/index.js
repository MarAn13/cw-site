$('.mainButtonImage').click(function () {
    $('#mainBackgroundImage').hide();
    $('#mainDivHidden').show();
});

var bodyHeight = $('body').height();
var headerHeight = $('header').height();
var footerHeight = $('footer').height();
$('.mainContentDiv').css('min-height', bodyHeight - headerHeight - footerHeight);