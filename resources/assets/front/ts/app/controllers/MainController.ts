namespace app.controllers
{
    export class MainController
    {
        public init()
        {
            this.automaticHeightRatioInit();
        }

        private automaticHeightRatioInit()
        {
            $(window).on('resize',function(){
                app.controllers.MainController.automaticHeightRatioCalculate();
            });

            app.controllers.MainController.automaticHeightRatioCalculate();
        }

        public static automaticHeightRatioCalculate(animate: boolean = false)
        {
            $('[data-auto-height-ratio]').each(function(){
                let heightPercent = parseInt($(this).data('auto-height-ratio'));

                let width = $(this).outerWidth();

                console.log(width);

                if(animate) {
                    $(this).animate({
                        height: (width / 100) * heightPercent
                    }, 200);
                }else{
                    $(this).css('height', (width / 100) * heightPercent + 'px');
                }
            });
        }
    }
}
