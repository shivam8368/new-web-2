namespace app.lib
{
    export class GlobalLoader
    {
        private static selector: string = '._loading';

        public static open()
        {
            if(!$(this.selector).length)
            {
                throw new Error('Selector '+this.selector+' not found');
            }

            $(this.selector).stop(true, true).fadeIn('fast');
        }

        public static close()
        {
            $(this.selector).stop(true, true).fadeOut('fast');
        }
    }
}