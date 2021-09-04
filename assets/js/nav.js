$('#signOutButton').hover(function (){
    $('#signOutClose').addClass('d-none');
    $('#signOutOpen').removeClass('d-none');
});
$('#signOutButton').mouseleave(function(){
    $('#signOutOpen').addClass('d-none');
    $('#signOutClose').removeClass('d-none');
});