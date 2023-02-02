$(function(){
    initDataAttributes();
    fullHeightPages();

    (new app.controllers.MainController()).init();
});


function fullHeightPages()
{
    if($('[data-full-height]').length == 0) {
        return;
    }

    $(window).on('resize', function(){
        let fullHeightElement = $('[data-full-height]');
        let navbar = $('.navbar-container');
        let divider = navbar.next().hasClass('divider') ? navbar.next() : null;
        let footer = $('[data-footer]');

        let minusHeight = navbar.innerHeight() + footer.innerHeight();
        if(divider !== null && divider.is(':visible')) {
            minusHeight += divider.innerHeight();
        }

        $(fullHeightElement).css('min-height', 'calc(100vh '+(divider !== null ? '+ 1.5vw' : '')+' - '+minusHeight+'px)');
    });


    $(window).trigger('resize');
}

function initDataAttributes()
{
    $(document).on('click', '[data-href]', function(event) {
        if($(this).attr('data-blank') || event.ctrlKey) {
            window.open($(this).data('href'), '_blank');
        }else{
            app.lib.GlobalLoader.open();

            if($(this).data('href') !==  window.location.pathname)  {
                window.location.href = $(this).data('href');
            }else{
                window.location.reload();
            }
        }
    });

    $('form').on('submit', function(){
        $(this).find('button[type="submit"]').each(function(){
            $(this).addClass('is-loading');
        });
    });
}
