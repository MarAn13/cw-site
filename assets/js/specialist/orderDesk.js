var bodyHeight = $('body').height();
var headerHeight = $('header').height();
var footerHeight = $('footer').height();
$('.mainContentDiv').css('min-height', bodyHeight - headerHeight - footerHeight);
$('.mainContentBgDiv').css('height', $('.mainContentDiv').height());
/*
if ($('.row.shadow:last').position().top + $('.row.shadow:last').outerHeight(true) + $('.row.shadow:last').outerHeight(true) > $('div.bg-dark').outerHeight()){
    $('.float-end').removeClass('d-none');
}
*/

$('#header_orderDesk').addClass('active');

$('.orderInfoDiv').hover(function () {
    $('.orderInfoDiv').addClass('orderInfoDivBlur');
    $(this).removeClass('orderInfoDivBlur');
});
$('.orderInfoDiv').mouseleave(function () {
    $('.orderInfoDiv').removeClass('orderInfoDivBlur');
});
/*
$('.page-link').click(function () {
    if ($.trim($(this).text()) == '\u00BB') { // >>
        var nextActiveElement = $('.active.page-link').parent().next().find('a');
        if (nextActiveElement[0] != $('.page-link:last')[0]) {

            $('.page-link').removeClass('active');
            nextActiveElement.addClass('active');
        }
    } else if ($.trim($(this).text()) == '\u00AB') { // <<
        var nextActiveElement = $('.active.page-link').parent().prev().find('a');
        if (nextActiveElement[0] != $('.page-link:first')[0]) {
            $('.page-link').removeClass('active');
            nextActiveElement.addClass('active');
        }
    } else {
        $('.page-link').removeClass('active');
        $(this).addClass('active');
    }
    if ($('.page-link').eq(-2).hasClass('active')){
        $('.page-link:last').hide();
        $('.page-link:first').show();
    }
    else if ($('.page-link').eq(1).hasClass('active')){
        $('.page-link:first').hide();
        $('.page-link:last').show();
    }
    else{
        $('.page-link:last').show();
        $('.page-link:first').show();
    }
});
*/