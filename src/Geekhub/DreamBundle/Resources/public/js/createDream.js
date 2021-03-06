jQuery(document).ready(function() {
    var $collectionFinHolder = $('ul.financialResources');
    var add_fin = Translator.trans("dream.js.add_financial");
    processCollectionHolder($collectionFinHolder, add_fin, 'add-financial-point');

    var $collectionEquipHolder = $('ul.equipmentResources');
    var add_equip = Translator.trans("dream.js.add_equipment");
    processCollectionHolder($collectionEquipHolder, add_equip, 'add-equipment-point');

    var $collectionWorkHolder = $('ul.workResources');
    var add_work = Translator.trans("dream.js.add_work");
    processCollectionHolder($collectionWorkHolder, add_work, 'add-work-point');
});

function processCollectionHolder($collectionHolder, addLinkText, linkId) {
    var $addLink   = $('<a href="#" id="' + linkId + '" title="' + addLinkText + '" class="btn-add"><span class="icon-add"></span></a>');
    var $newLinkLi = $('<li class=""></li>').append($addLink);

    $collectionHolder.find('li.collection-form').each(function() {
        appendDeleteLink($(this));
    });

    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addLink.on('click', function(e) {
        e.preventDefault();
        addFormToCollection($collectionHolder, $newLinkLi);
    });
}

function addFormToCollection($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    var $newFormLi = $('<li class="input-block"></li>').append(newForm);
    $newFormLi.find('input').each(function(){
        //$(this).addClass('form-control');
    });
    $newFormLi.find('select').each(function(){
        //$(this).addClass('form-control');
    });

    $collectionHolder.data('index', index + 1);
    $newLinkLi.before($newFormLi);
    appendDeleteLink($newFormLi);
}

function appendDeleteLink($li) {
    var delete_resource = Translator.trans("dream.js.delete_resource");
    //var $deleteLink = $('<a href="#" class="btn btn-xs btn-danger pull-right">' + delete_resource + '</a>');
    var $deleteLink = $('<a href="#" class="close">&times;</a>');
    
    
    $li.append($deleteLink);

    $deleteLink.on('click', function(e) {
        e.preventDefault();
        $li.remove();
    });
}
