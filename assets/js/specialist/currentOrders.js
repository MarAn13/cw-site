var bodyHeight = $('body').height();
var headerHeight = $('header').height();
var footerHeight = $('footer').height();
$('.mainContentDiv').css('min-height', bodyHeight - headerHeight - footerHeight);
$('.mainContentBgDiv').css('height', $('.mainContentDiv').height());

$('#header_currentOrders').addClass('active');

$('.orderInfoDiv').hover(function () {
    $('.orderInfoDiv').addClass('orderInfoDivBlur');
    $(this).removeClass('orderInfoDivBlur');
});
$('.orderInfoDiv').mouseleave(function () {
    $('.orderInfoDiv').removeClass('orderInfoDivBlur');
});