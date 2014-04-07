        $('#dreams').on('click', 'span', function(event) {
            var itemObj = $(event.target);
            if (itemObj.data('sended') == 'yes') {
                console.log('already in favorite');
                return;
            }
            var path = Routing.generate('dream_ajax_dream_toFavorite');
            $.post(path, {id : $(event.target).data('idmedia')})
                .done(function(msgDone) {
                    console.log(msgDone);
                    itemObj.css({color:'green'});
                    itemObj.data('sended', 'yes');
                    itemObj.attr('title', 'уже в улюблених.');
                    $('#added_success').append(
                        '<div class="alert alert-info" style="margin-top: 20px;"> <strong>Мрію успішно додано до списку улюблених.</strong>' +
                                '&nbsp; &nbsp; <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> &nbsp;&nbsp; ' +
                        '</div>'
                    );
                })
                .error(function(msg) {
                    console.log(msg);
                }
            );
        });
