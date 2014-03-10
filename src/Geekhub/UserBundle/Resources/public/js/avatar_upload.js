
$('#user-avatar-image').click(function() {
    var url = Routing.generate('dream_ajax_load_poster');
    $('#dream_poster').fileupload({
        url: url,
        dataType: 'json',
        done: function (e, data) {
            alert(data);
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
                    $('#user-avatar-container').html('<img src="' + file.srcPreview + '" class="img-thumbnail">' +
                        '<input id="dream-poster" type="file" name="dream-poster">');
                    $('#newUserForm_avatar').get(0).value = file.src;
                }
            });

        },
        error: function(msg) {
            alert(msg);
            console.log('error = ' + msg);
            console.dir(msg);
        }
    });
});
