;$('#dream-gallery img').click(function() {
    var imgObj = $(this);
    if (confirm("Видалити елемент?")) {
        var path = Routing.generate('dream_ajax_remove_media');
        $.post(path, {id : $(this).data('idmedia')})
            .done(function(){
                imgObj.remove();
            })
            .error(function(msg){
                console.log(msg);
            });
    } else {
        console.log('cancel');
    }
});

$('#dream-files a').click(function() {
    var imgObj = $(this);
    if (confirm("Видалити елемент?")) {
        var path = Routing.generate('dream_ajax_remove_media');
        $.post(path, {id : $(this).data('idmedia')})
            .done(function(){
                imgObj.remove();
            })
            .error(function(msg){
                console.log(msg);
            });
    } else {
        console.log('cancel');
    }
    return false;
});

$('#dream-videos img').click(function() {
    var imgObj = $(this);
    if (confirm("Видалити елемент?")) {
        var path = Routing.generate('dream_ajax_remove_media');
        $.post(path, {id : $(this).data('idmedia')})
            .done(function(){
                imgObj.remove();
            })
            .error(function(msg){
                console.log(msg);
            });
    } else {
        console.log('cancel');
    }
});
