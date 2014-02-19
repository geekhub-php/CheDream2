;$(function () {
    'use strict';

    // Define the url to send the image data to
    var url = Routing.generate('dream_ajax_load_image');

    // Call the fileupload widget and set some parameters
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            // Add each uploaded file name to the #files list
            $.each(data.result.files, function (index, file) {
                $('<li/>').text(file.name).appendTo('#files');
            });
        },
        progressall: function (e, data) {
            // Update the progress bar while files are being uploaded
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    });
});