        $('#dreams').on('click', 'span', function(event) {
            var itemObj = $(event.target);
            if (itemObj.data('sended') == 'yes') {
                console.log('del from fav');
                var path1 = Routing.generate('dream_ajax_dream_fromFavorite');
                $.post(path1, {id : $(event.target).data('idmedia')})
                    .done(function(msgDone) {
                        console.log(msgDone);
                        itemObj.css({color:'yellow'});
                        itemObj.data('sended', 'no');
                        itemObj.attr('title', 'додати до списку улюблених.');
                        $('#added_success').append(
                            '<div class="alert alert-info" style="margin-top: 20px;"> <strong>Мрію видалено із списку улюблених.</strong>' +
                                '&nbsp; &nbsp; <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> &nbsp;&nbsp; ' +
                                '</div>'
                        );
                    })
                    .error(function(msg) {
                        console.log(msg);
                    }
                );

                return;
            }
            if (itemObj.data('sended') == 'no') {
                console.log('add to fav');
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
            }
        });
