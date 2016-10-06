tinymce.init({
    selector: '#post_content',
    browser_spellcheck: true,
    height: 500,
    plugins: [
        'advlist autolink lists link image charmap print preview anchor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    content_css: '//www.tinymce.com/css/codepen.min.css' // TODO: create a style
});

$(function(){
    $("#post_type").change(function(){
        var value = $(this).val();

        console.log(value);

        $form = $("#form_post");

        $form.find("#group_title").removeClass("hidden");
        $form.find("#group_categories").removeClass("hidden");
        $form.find("#group_tags").removeClass("hidden");

        $form.find("#group_caption").addClass("hidden");
        $form.find("#group_link").addClass("hidden");
        $form.find("#group_content").addClass("hidden");
        $form.find("#upload_input").addClass("hidden");


        switch (value) {
            case 'link':
                $form.find("#group_caption").removeClass("hidden");
                $form.find("#group_link").removeClass("hidden");
                break;
            case 'picture':
                $form.find("#group_caption").removeClass("hidden");
                $form.find("#group_link").removeClass("hidden");
                $form.find("#upload_input").removeClass("hidden");
                break;
            case 'text':
                $form.find("#group_content").removeClass("hidden");
                break;
            case 'video':
                $form.find("#group_caption").removeClass("hidden");
                $form.find("#group_link").removeClass("hidden");
                break;
            default:
                $form.find("#group_title").addClass("hidden");
                $form.find("#group_categories").addClass("hidden");
                $form.find("#group_tags").addClass("hidden");
                $form.find("#group_caption").addClass("hidden");
                $form.find("#group_link").addClass("hidden");
                $form.find("#group_content").addClass("hidden");
        }
    })
});
