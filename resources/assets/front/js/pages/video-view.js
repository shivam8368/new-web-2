$(function(){
    $('[data-large-panel]').on('click', function(){
        if(!$('[data-right-side]').is(':visible') || $('[data-left-side]').is(':animated')) {
            return;
        }

        $('[data-left-side]').css('transition', 'ease-out all 0.4s');
        $('[data-right-side]').fadeOut('fast');
        $('[data-large-panel]').fadeOut('fast');

        let currentHeight = $('[data-left-side] iframe').outerHeight();
        let newHeight = currentHeight + ((currentHeight / 100) * 30);

        $('[data-left-side]').css('width', '100%');
        $('[data-left-side] iframe').animate({
            height: newHeight
        }, 200);
    });
});
