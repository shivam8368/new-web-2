$(function(){
    $('[data-dropdown]').on('click', function(){
        let dropdownName = $(this).data('dropdown');

        let dropdownContentElement = $('[data-dropdown-content="'+dropdownName+'"]');
        dropdownContentElement.stop();

        if(dropdownContentElement.is(':visible')) {
            dropdownContentElement.fadeOut(120);
        }else{
            dropdownContentElement.fadeIn(120);
        }
    });
});
