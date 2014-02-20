;jQuery(function($){
    var path = Routing.generate('tag_get_tags');

    $.get(path).done(function(response){
//        console.log(response);
        var tags = JSON.parse(response);
        var parsedTags = new Array();

        tags.forEach(function (element) {
            parsedTags.push(element.name);
        });

//        console.log(parsedTags);
        $('#newDreamForm_tags').tagit({
            availableTags: parsedTags,
            allowSpaces: true
        });
    });
});
