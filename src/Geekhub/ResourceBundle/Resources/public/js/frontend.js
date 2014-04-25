$(function() {
    
    var $header = $('.header');
    
    $header.find('.search-link').on('click', function() {
        $header.addClass('search-active');
    });
    
    $(document).on('click', function(e) {
        var $target = $(e.target);
        if ($target.closest('.header').length && $target.closest('.search-block').length) return;
        $header.removeClass('search-active');
    });
    
    
    $header.find('.show-dreams').on('click', function(e) {
        if ($header.hasClass('dreams-active')) return;
        e.stopPropagation();
        $header.addClass('dreams-active');
    });
    
    $(document).on('click', function(e) {
        var $target = $(e.target);
        if ($target.closest('.header').length && $target.closest('.dreams-list').length) return;
        $header.removeClass('dreams-active');
    });
    
    
    if ($('.custom-radio').length) {
        executeCustomRadio();
    }
    
    $(document).on('click', '.custom-radio', function() {
        executeCustomRadio();
    });
    
    function executeCustomRadio($this) {
        $('.custom-radio').each(function() {
            var $this = $(this);
            $this.toggleClass('active', $this.find('input').prop('checked'));
            $this.toggleClass('disabled', $this.find('input').prop('disabled'));
        });
    }
    
});