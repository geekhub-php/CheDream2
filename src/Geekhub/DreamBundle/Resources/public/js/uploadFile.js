$(document).ready(function() {
    $('#dream-add-video').hide();

    if (localStorage.getItem('newDreamPoster')) {
        var uploadPoster = localStorage.getItem('newDreamPoster');
        $('#newDream-poster-image').attr('src', uploadPoster);
    }

    if (localStorage.getItem('newDreamMediaFiles')) {
        var uploadMediaPicturesAndFiles = localStorage.getItem('newDreamMediaFiles');
        $('#media-block-container').html(localStorage.getItem('newDreamMediaFiles'));
    }
});

$('#dream-poster-image').click(function() {
    var url = Routing.generate('dream_ajax_load_poster');
    $('#fileupload-poster').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            var responseArr = JSON.parse(data.jqXHR.responseText);
            $.each(responseArr, function (index, file) {
                if(file.error){
                    $('#errors').append(
                        '<div class="alert alert-danger">' + file.src + ' <strong>' +file.error + '</strong>' +
                            '&nbsp; &nbsp; <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> &nbsp;&nbsp; ' +
                            '</div>'
                    );

                    return;
                }
                if(file.type == 'image' && file.error == null) {
                    $('#dream-poster-container').html('<img src="' + file.srcPreview + '" class="img-thumbnail">' +
                        '<input id="fileupload-poster" type="file" name="dream-poster">');
                    $('#newDreamForm_dreamPoster').get(0).value = file.src;

                    var posterNewDream = file.srcPreview;
                    localStorage.setItem('newDreamPoster', posterNewDream);
                }
            });

        },
        error: function(msg) {
            console.log('error = ' + msg);
            console.dir(msg);
        }
    });
});

loadFile = function () {
//            'use strict';
//        var url = Routing.generate('media_upload_images');
    var url = Routing.generate('dream_ajax_load_image');
    $('#fileuploadz').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            var responseArr = JSON.parse(data.jqXHR.responseText);
            $.each(responseArr, function (index, file) {
                if(file.error){
                    $('#errors').append(
                        '<div class="alert alert-danger">' + file.src + ' <strong>' +file.error + '</strong>' +
                            '&nbsp; &nbsp; <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> &nbsp;&nbsp; ' +
                            '</div>'
                    );

                    return;
                }
                if(file.type == 'image' && file.error == null) {
                    $('#dream-gallery').append('<img src="' + file.srcPreview + '" class="img-thumbnail">');
                    $('#newDreamForm_dreamPictures').get(0).value += ',' + file.src;
                }
                if(file.type == 'file' && file.error == null) {
                    $('#dream-files').append(
                        '<div class="alert alert-info">' +
                            '<span class="glyphicon glyphicon-file"></span>' + ' ' + file.name +
                            '</div>'
                    );
                    $('#newDreamForm_dreamFiles').get(0).value += ',' + file.src;
                }
            });

            var pictureAndFiles = $('#media-block-container').html();
            localStorage.setItem('newDreamMediaFiles', pictureAndFiles);
        },
        error: function(msg) {
            console.log('error = ' + msg);
            console.dir(msg);
        }
    });
};

addDreamVideo = function() {
    $('#dream-add-video').fadeIn(500).show();
};

$('#add-video-button').click(function() {
    var videoSrc = $('#dream-add-video-text-input').get(0).value;
    if (videoSrc != '' ) {
        $('#newDreamForm_dreamVideos').get(0).value += ',' + videoSrc;
        $('#dream-add-video-text-input').get(0).value = '';
        $('#dream-add-video').fadeOut(500).hide();

        $('#dream-videos').append(
            '<div class="alert alert-warning">' +
                '<i class="icon icon-youtube"></i>' + 'Youtube video' + '</div>'
        );
        $('#dream-add-video-error').html('');

        var pictureAndFiles = $('#media-block-container').html();
        localStorage.setItem('newDreamMediaFiles', pictureAndFiles);
    } else {
        $('#dream-add-video-error').html(
            '<div class="alert alert-warning alert-dismissable">' +
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                '<strong>Увага!</strong> Ви не додали адресу на відео' +
                '</div>'
        );
    }
});

$('#add-video-button-cancel').click(function() {
    $('#dream-add-video-error').html('');
    $('#dream-add-video-text-input').get(0).value = '';
    $('#dream-add-video').fadeOut(500).hide();
});
