/**
 *  Converting SVG icons in icons dir
 */

$(function(){
    let iconsMap = @@include('../dist/icons.json');

    function replaceIcons()
    {
        // Init
        $('[data-icon]').each(function()
        {
            let icon = $(this).data('icon');
            if(!iconsMap.hasOwnProperty(icon)) {
                $(this).remove();
                console.error('Icon ' + icon + ' not found.');
                return true;
            }

            let style = $(this).attr('style');
            let cls = $(this).classList();
            let onClick = $(this).attr('onclick');

            let newElement = $(this).replaceWithPush(iconsMap[icon]);

            if(typeof style !== 'undefined') {
                newElement.attr('style', style);
            }

            if(typeof onClick !== 'undefined') {
                newElement.attr('onclick', onClick);
            }

            // Each data attribute
            $.each($(this).get(0).attributes, function(index, attr) {
                if (/^data\-(.+)$/.test(attr.nodeName) && attr.nodeName !== 'data-icon') {
                    newElement.attr(attr.nodeName, attr.nodeValue);
                }
            });

            $(cls).each(function(i, val){
                newElement.addClass(val);
            });
        });
    }

    $('body').on('domchange', function(){
        replaceIcons();
    });

    $.fn.replaceWithPush = function(a) {
        let $a = $(a);
        this.replaceWith($a);
        return $a;
    };
    $.fn.classList = function() {return this[0].className.split(/\s+/);};

    replaceIcons();
});