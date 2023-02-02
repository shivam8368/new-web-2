$(function(){
    if($('[data-swipe-preview-gif]').length && $('[data-swipe-preview-gif]').is(':visible')) {
        window['swipePreviewGifInterval'] = setInterval(function(){
            if(!$('[data-swipe-preview-gif]').length || !$('[data-swipe-preview-gif]').is(':visible')) {
                clearInterval(window['swipePreviewGifInterval']);
                return;
            }

            $('[data-swipe-preview-gif]').attr('src', $('[data-swipe-preview-gif]').attr('src'));
        }, 3000);
    }

    $('[data-swipe-preview-info-close]').on('click', function(){
        localStorage.setItem('swipe-preview-info', '1');
        $('[data-swipe-preview-info]').slideUp(300);
    });
});
