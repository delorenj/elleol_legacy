$(document).ready(function() {

    if(typeof(qq) != "undefined") {
        var uploader = new qq.FileUploader({
            element: $('#imageUploadButton')[0],
            action: $("#upload-area").attr("data-action"),
            debug: false,
            onComplete: function(id, fileName, responseJSON){
                var src = "/img/products/" + fileName;
                $("#thumb").attr("src", src);
                $("#product_image").val(src);
            }
        });        
    }

    $("ul.products li")
        .mouseenter(function() {
            $(this).find(".thumbnail .action-panel").fadeTo(100, 0.75).find(".btn-toolbar").fadeTo(100, 1);
        })
        .mouseleave(function() {
            $(this).find(".thumbnail .action-panel").fadeTo(100, 0).find(".btn-toolbar").fadeTo(100, 0);;
        });

});