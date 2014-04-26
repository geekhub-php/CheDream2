$(document).ready(function() {
    //$('#user-avatar-image input').on('click', function(e) {
    //    e.stopPropagation();
    //});
});

$('#user-avatar-image').click(function() {
    var $block = $(this);
    var url = Routing.generate('dream_ajax_load_poster');
    
    $block.find('input').click();
    $('#dream_poster').fileupload({
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
                    $('#newUserForm_avatar').get(0).value = file.src;
                }
            });
        },
        error: function(msg) {
            console.log('error = ' + msg);
            console.dir(msg);
        }
    });
});
