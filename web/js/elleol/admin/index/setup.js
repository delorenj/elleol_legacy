$(document).ready(function() {

    var uploader = new qq.FileUploader({
        element: $('#imageUploadButton')[0],
        action: $("#upload-area").attr("data-action"),
        debug: false,
		onComplete: function(id, fileName, responseJSON){
			$("#thumb").attr("src", "/img/products/" + fileName);
		}
    });  

})