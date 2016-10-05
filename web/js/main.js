/**
 * Created by Student on 05-10-16.
 */
$(document).ready(function(){
    $("#post_categories").change(function(){
        $("#post_categories option:selected").click(function(){
            var value = $(this).val();
            var input = $(".is"+ value);

            console.log(input);

            $(input).toggle(function(){
                $(this).removeClass("hidden");
            }), function(){
                $(this).addClass("hidden");
            }
        })

    })
});
