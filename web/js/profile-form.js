$(function(){
    var link = $("#toggle-form");

    link.on('click', function() {
        $("#profile").toggle();
        $("#profile_form").toggle();
    });
});