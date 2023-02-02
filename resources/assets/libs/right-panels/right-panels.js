$(function(){
    window['openRightPanel'] = function(panelId) {
        let scrollTop = $(window).scrollTop();
        $('#' + panelId).addClass('is-active');
        $(window).trigger('right-panel-open', [panelId]);

        $('html').addClass('lock-scrolling');
        $(window).scrollTop(scrollTop);
    };

    $('.panel-overlay, .right-panel .close-panel, [data-close-panel]').on('click', function () {
        let panelWrapper = $(this).closest('.right-panel-wrapper');
        panelWrapper.removeClass('is-active');
        $('html').removeClass('lock-scrolling');
        panelWrapper.trigger('close');
    });

    if ($('.right-panel-trigger').length) {
        $('.right-panel-trigger').on('click', function () {
            window['openRightPanel']($(this).attr('data-panel'));
        });
    }

})
