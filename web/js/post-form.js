tinymce.init({
    selector: 'textarea',
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

// $(document).ready(function(){
//     $("#post_categories").change(function(){
//         $("#post_categories option:selected").click(function(){
//             var value = $(this).val();
//             var input = $(".is"+ value);
//
//             console.log(input);
//
//             $(input).toggle(function(){
//                 $(this).removeClass("hidden");
//             }), function(){
//                 $(this).addClass("hidden");
//             }
//         })
//
//     })
// });
