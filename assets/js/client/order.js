$('#videoTypeSelection').change(function () {
    if ($('#videoTypeSelection').val() == "livestream") {
        $('#videoDaySelection').addClass('d-none');
        $('#videoDaySelection').val("");
        $('#dateTimeWrap').removeClass('d-none');
    } else if ($('#videoTypeSelection').val() == "video") {
        $('#videoDaySelection').removeClass('d-none');
        $('#dateTimeWrap').addClass('d-none');
    } else {
        $('#videoDaySelection').addClass('d-none');
        $('#videoDaySelection').val("");
        $('#dateTimeWrap').addClass('d-none');
    }
});

var bodyHeight = $('body').height();
var headerHeight = $('header').height();
var footerHeight = $('footer').height();
$('.mainContentDiv').css('min-height', bodyHeight - headerHeight - footerHeight);
$('.mainContentBgDiv').css('height', $('.mainContentDiv').height());

$(document).ready(function () {
    $(function () {
        let dateNow = new Date(Date.now());
        let dateList = [];
        let nextDay = new Date();
        for (let i = 8; i < 15; i++) {
            nextDay.setDate(dateNow.getDate() + i);
            dateList.push(moment(nextDay));
        }
        $('#datetimepicker').datetimepicker({
            inline: true,
            sideBySide: true,
            locale: 'ru',
            /* minDate: dateList[0].set({
                 'hour': 0,
                 'minute': 0
             }),
             maxDate: dateList[6].set({
                 'hour': 23,
                 'minute': 59
             }),*/
            minDate: dateList[0].hour(0).minute(0),
            maxDate: dateList[6].hour(23).minute(59),
            icons: {
                up: 'bi bi-arrow-up-short',
                down: 'bi bi-arrow-down-short',
                previous: 'bi bi-arrow-left-short',
                next: 'bi bi-arrow-right-short'
            },
            viewMode: 'days'
        });
        $($('th.disabled').find('span.bi')).css('color', '#777');
        $('th.picker-switch').addClass('disabled');
        $('th.picker-switch').css('color', 'white');
        $('th.picker-switch').hover(function () {
            $('th.picker-switch').css('background-color', 'transparent');
        });
        $('#datetimepicker').datetimepicker().on('dp.update', function (event) {
            $('th.picker-switch').addClass('disabled');
            $('th.picker-switch').css('color', 'white');
            $('th.picker-switch').hover(function () {
                $('th.picker-switch').css('background-color', 'transparent');
            });
            $($('th.disabled').find('span.bi')).css('color', '#777');
            $($('th').not('.disabled').find('span.bi')).css('color', 'white');
        });
        $('#datetimepicker').datetimepicker().on('dp.change', function (event) {
            $('th.picker-switch').addClass('disabled');
            $('th.picker-switch').css('color', 'white');
            $('th.picker-switch').hover(function () {
                $('th.picker-switch').css('background-color', 'transparent');
            });
            $($('th.disabled').find('span.bi')).css('color', '#777');
            $($('th').not('.disabled').find('span.bi')).css('color', 'white');
        });
    });
});