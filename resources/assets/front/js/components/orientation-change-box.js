$(function(){
    $('[data-orientation-box-button]').on('click', function(){
        let newOrientation = $(this).data('orientation-box-button');
        if(newOrientation === '') {
            $('[data-orientation-box]').fadeOut('fast');
            (new app.services.HttpClient()).getAsync('/set/orientation-box-closed').then();
            return;
        }

        location.href = '/set/orientation/' + newOrientation + '?next=' + encodeURIComponent(window['currentUri']);
    });

    $(document).on('click', '[data-orientation-box] .close-button', function(){
        $('[data-orientation-box]').fadeOut('fast');
        (new app.services.HttpClient()).getAsync('/set/orientation-box-closed').then();
    });
});
