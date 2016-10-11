$(function(){
    var postView = $("#post_view");
    var filter = $("#filter");
    var type;

    var postCaption = $("#post_caption");
    var postLink = $("#post_link");
    var postVideo = $("#post_video");
    var postContent = $("#post_content");
    var footer = postView.find("footer");

    //Open Event
    $(".post").click(function(){
        type = $(this).attr("data-type");

        postView.css("top", $(document).scrollTop()+60);

        //Common to every posts
        $("#post_title").html($(this).find(".post-title").html());
        footer.html($(this).find(".panel-footer").html());

        switch (type) {
            case 'link':
                postLink.html($(this).find(".post-link").html());
                postCaption.html($(this).find(".post-caption").html());
                break;
            case 'picture':
                postLink.html($(this).find(".post-picture").html());
                postCaption.html($(this).find(".post-caption").html());
                break;
            case 'text':
                postContent.html($(this).find(".post-content").html());
                break;
            case 'video':
                postVideo.html($(this).find(".post-video").html());
                //For embed video bootstrap
                postVideo.show();
                postCaption.html($(this).find(".post-caption").html());
                break;
        }

        filter.show();
        postView.show();
    });

    //Close Events
    $("#post_view_close").click(function() {
        closePost();
    });
    filter.click(function() {
        closePost();
    });

    function closePost() {
        filter.hide();
        postView.hide();

        //Unload post view
        postCaption.html("");
        postLink.html("");
        postContent.html("");
        postVideo.html("");
        postVideo.hide();
    }

});
