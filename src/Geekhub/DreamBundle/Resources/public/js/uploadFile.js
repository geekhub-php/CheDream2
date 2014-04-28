var arrayMediaFiles = [];
$(document).ready(function() {
    $('#dream-add-video').hide();
    
    $('#dream-poster-image input').on('click', function(e) {
        e.stopPropagation();
    });
    $('.upload-buttons .upload-image, .upload-buttons .upload-file').on('click', function(e) {
        $(this).siblings('input').click();
    });

    if (localStorage.getItem('newDreamPoster')) {
        var uploadPoster = localStorage.getItem('newDreamPoster');
        $('#dream-poster-image').html(localStorage.getItem('newDreamPoster'));
    }

    if (localStorage.getItem('newDreamMediaFiles')) {
        var uploadMediaPicturesAndFiles = localStorage.getItem('newDreamMediaFiles');
        $('#media-block-container').html(localStorage.getItem('newDreamMediaFiles'));
    }

});

$('#dream-poster-image').on('click', function(e) {
    
    var $block = $(this);
    var url = Routing.generate('dream_ajax_load_poster');
    
    $block.find('input').click();
    $('#fileupload-poster').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            var responseArr = JSON.parse(data.jqXHR.responseText);
            $.each(responseArr, function (index, file) {
                if(file.error){
                    $('#errors').append(
                        '<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            file.src + ' <strong>' +file.error + '</strong>' +
                        '</div>'                        
                    );
                    
                    return;
                }
                if(file.type == 'image' && file.error == null) {
                    $block.addClass('active').find('img').attr('src', file.srcPreview);
                    $('#newDreamForm_dreamPoster').get(0).value = file.src;
                }
            });
            var posterNewDream = $('#dream-poster-image').html();
            localStorage.setItem('newDreamPoster', posterNewDream);
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
    $(this).closest('.upload-buttons').find('input').click();
    $('#fileuploadz').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            var responseArr = JSON.parse(data.jqXHR.responseText);
            $.each(responseArr, function (index, file) {
                if(file.error){
                    $('#errors').append(
                        '<div class="alert alert-danger">' +
                            '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                            file.src + ' <strong>' +file.error + '</strong>' +
                        '</div>'
                    );

                    return;
                }
                if(file.type == 'image' && file.error == null) {
                    $('#dream-gallery').append('<img src="' + file.srcPreview + '" alt="Image">');
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
