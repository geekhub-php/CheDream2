$(function() {
    
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