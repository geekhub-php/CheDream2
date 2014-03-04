jQuery(document).ready(function() {
    var $collectionFinHolder, $collectionEquipHolder, $collectionWorkHolder;

    var add_fin = Translator.trans("dream.js.add_financial");
    var $addFinLink = $('<a href="#" class="add_fin_link btn btn-xs btn-default text-left">' + add_fin + '</a>');
    var $newFinLinkLi = $('<li class="list-unstyled"></li>').append($addFinLink);

    var add_equip = Translator.trans("dream.js.add_equipment");
    var $addEquipLink = $('<a href="#" class="add_equip_link btn btn-xs btn-default text-left">' + add_equip + '</a>');
    var $newEquipLinkLi = $('<li class="list-unstyled"></li>').append($addEquipLink);

    var add_work = Translator.trans("dream.js.add_work");
    var $addWorkLink = $('<a href="#" class="add_work_link btn btn-xs btn-default text-left">' + add_work + '</a>');
    var $newWorkLinkLi = $('<li class="list-unstyled"></li>').append($addWorkLink);

    $collectionFinHolder = $('ul.financialResources');
    $collectionEquipHolder = $('ul.equipmentResources');
    $collectionWorkHolder = $('ul.workResources');

    $collectionFinHolder.find('li').each(function() {
        addFinFormDeleteLink($(this));
    });

    $collectionEquipHolder.find('li').each(function() {
        addEquipFormDeleteLink($(this));
    });

    $collectionWorkHolder.find('li').each(function() {
        addWorkFormDeleteLink($(this));
    });

    $collectionFinHolder.append($newFinLinkLi);
    $collectionEquipHolder.append($newEquipLinkLi);
    $collectionWorkHolder.append($newWorkLinkLi);

    $collectionFinHolder.data('index', $collectionFinHolder.find(':input').length);
    $collectionEquipHolder.data('index', $collectionEquipHolder.find(':input').length);
    $collectionWorkHolder.data('index', $collectionWorkHolder.find(':input').length);

    $addFinLink.on('click', function(e) {
        e.preventDefault();
        addFinForm($collectionFinHolder, $newFinLinkLi);
    });
    $addEquipLink.on('click', function(e) {
        e.preventDefault();
        addEquipForm($collectionEquipHolder, $newEquipLinkLi);
    });
    $addWorkLink.on('click', function(e) {
        e.preventDefault();
        addWorkForm($collectionWorkHolder, $newWorkLinkLi);
    });
});

function addFinForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    var $newFormLi = $('<li class="list-unstyled"></li>').append('<br>' + newForm);
    $newFormLi.find('input').each(function(){
        $(this).addClass('form-control');
    });
    $collectionHolder.data('index', index + 1);
    $newLinkLi.before($newFormLi);
    addFinFormDeleteLink($newFormLi);
}

function addEquipForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    var $newFormLi = $('<li class="list-unstyled"></li>').append('<br>' + newForm);
    $newFormLi.find('input').each(function(){
        $(this).addClass('form-control');
    });
    $newFormLi.find('select').each(function(){
        $(this).addClass('form-control');
    });

    $collectionHolder.data('index', index + 1);
    $newLinkLi.before($newFormLi);
    addEquipFormDeleteLink($newFormLi);
}

function addWorkForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);
    var $newFormLi = $('<li class="list-unstyled"></li>').append('<br>' + newForm);
    $newFormLi.find('input').each(function(){
        $(this).addClass('form-control');
    });

    $collectionHolder.data('index', index + 1);
    $newLinkLi.before($newFormLi);
    addWorkFormDeleteLink($newFormLi);
}

function addFinFormDeleteLink($finFormLi) {
    var delete_resource = Translator.trans("dream.js.delete_resource");
    var $removeFormA = $('<a href="#" class="delete_fin_link btn btn-xs btn-danger pull-right">' + delete_resource + '</a>');
    $finFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $finFormLi.remove();
    });
}

function addEquipFormDeleteLink($finFormLi) {
    var delete_resource = Translator.trans("dream.js.delete_resource");
    var $removeFormA = $('<a href="#" class="delete_equip_link btn btn-xs btn-danger pull-right">' + delete_resource + '</a>');
    $finFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $finFormLi.remove();
    });
}

function addWorkFormDeleteLink($finFormLi) {
    var delete_resource = Translator.trans("dream.js.delete_resource");
    var $removeFormA = $('<a href="#" class="delete_work_link btn btn-xs btn-danger pull-right">' + delete_resource + '</a>');
    $finFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();
        $finFormLi.remove();
    });
}
