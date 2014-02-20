loadImageGallery = function (typeMediaIn) {
//            'use strict';

    var url = 'ajax.php';
    var typeMedia = typeMediaIn;

    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                console.log(file);

                if(file.error){
                    $('<li/>').text(file.error).appendTo('#files');

                    return;
                }

                $('<li/>').text(file.name).appendTo('#files');
                $('#dream-gallery').append('<img src="' + file.thumbnail_url + '">');
                $('#dto').get(0).value += ',upload/' + file.name;

            });
        }
    });
};
