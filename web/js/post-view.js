$(function(){
    var postView = $("#post_view");

    $(".post").click(function(){
        console.log('display');

        postView.slideDown("slow", function() {

        });
    });
});
