$(document).ready(function() {

    if(typeof(qq) !== "undefined") {
        var uploader = new qq.FileUploader({
            element: $('#imageUploadButton')[0],
            action: $("#upload-area").attr("data-action"),
            debug: false,
            onComplete: function(id, fileName, responseJSON){
                var src = "/img/products/" + fileName;
                var rx = 240;
                var ry = 340;
                var ratio = rx/ry;

                var updateCoords = function(c) {
                    $("#x").val(c.x);
                    $("#y").val(c.y);
                    $("#w").val(c.w);
                    $("#h").val(c.h);
                    $("#prething").JcropPreviewUpdate(c);
                };

                $("#full").parent().show();
                $("#full").attr("src", src);

                $("#prething").show().JcropPreview({ 
                    jcropImg: $("#full"),
                    defaultWidth: 240,
                    defaultHeight: 340
                 });
                $("#thumb").closest("li").hide();
                $("#prething").closest("li").show();
                $("#full").Jcrop({
                    aspectRatio: ratio,
                    onSelect: updateCoords,
                    onChange: updateCoords
                });

                $("#btn_crop").click(function() {
                    $.post($(this).attr("data-action"), {
                        filepath: src,
                        x: $("#x").val(),
                        y: $("#y").val(),
                        w: $("#w").val(),
                        h: $("#h").val()
                    }, function(response) {
                        if(response.success) {
                            console.log(JSON.stringify(response));
                            $("#full").parent().hide();
                            $("#prething").hide();
                            $("#thumb").attr("src", response.src).closest('li').show();
                            $("#product_image").val(response.src);
                        }
                    }, "json");

                    return false;
                });
            }
        });  
        $("#imageUploadButton").css("margin", "0"); 
    }

    $("ul.products li")
        .mouseenter(function() {
            $(this).find(".thumbnail .action-panel").fadeTo(100, 0.75).find(".btn-toolbar").fadeTo(100, 1);
        })
        .mouseleave(function() {
            $(this).find(".thumbnail .action-panel").fadeTo(100, 0).find(".btn-toolbar").fadeTo(100, 0);
    });

});
