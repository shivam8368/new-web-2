namespace app.lib
{
    export class GlobalModal
    {
        private modalOpened:boolean = false;
        private bodyScrollTopPosition:number|null = null;

        constructor()
        {
            let _this = this;

            if(!$('#modal').length) {

                $('body').append('' +
                    '<div id="modal">\n' +
                    '   <div class="_modal-close">\n' +
                    '       <i data-icon="feather/x"></i>\n' +
                    '   </div>\n' +
                    '\n' +
                    '   <div id="modal-content" class="animated" style="overflow-x: hidden;">\n' +
                    '       <div style="width:100%;height:100%;"></div>' +
                    '   </div>\n' +
                    '</div>');
            }

            $(function() {
                $('._modal-close').on('click', function () {
                    _this.close();
                });

                $(document).keyup(function(e) {
                    if (e.key === "Escape") {
                        if($('#modal').is(':visible')) {
                            _this.close();
                        }
                    }
                });

                $(document).on('mousedown', function(event){
                    if(!_this.modalOpened) {
                        return;
                    }

                    if(!$(event.target).closest('#modal-content').length && !$(event.target).closest('.toast').length) {
                        _this.close();
                    }
                });
            });
        }


        public open(addToUrl:string = null)
        {
            let _this = this;

            if(addToUrl !== null) {
                window.location.hash = addToUrl;
            }

            this.bodyScrollTopPosition = $(window).scrollTop();

            $('html').addClass('lock-scrolling');
            $('#modal').css('display', 'block');
            $('#modal-content div:first').removeClass('animated fadeInLeft');

            $(window).scrollTop(this.bodyScrollTopPosition);

            if($(window).width() > 1179) {
                $('#modal-content').addClass('fadeInDown');
            }

            this.scrollToTop();

            // Animation finished
            setTimeout(function()
            {
                _this.modalOpened = true;
            }, 200);
        }

        public scrollToTop()
        {
            $('#modal-content div:first').scrollTop(0);
        }

        public scrollToBottom()
        {
            $('#modal-content div:first').scrollTop($('#modal-content div:first').height());
        }

        public close()
        {
            if(window.location.hash != '') {
                window.location.hash = '';
            }

            $('#modal-content').removeClass('fadeInDown');
            $('#modal').css('display', 'none');
            $('html').removeClass('lock-scrolling');
            this.modalOpened = false;
            this.bodyScrollTopPosition = null;
        }

        public setContent(content: string = null)
        {
            $('#modal-content div:first').html(content);
            return this;
        }

        public setContentFromUrl(url: string, afterFinish: Function = null, loader:boolean = true)
        {
            if(loader)
            {
                $('#modal-content div:first').html('<div style="display:flex;align-items: center;justify-content: center;width:100%;height:100%;"><div class="loader is-loading is-large"></div></div>');
            }

            (async function(){
                let HttpClient = new app.services.HttpClient();
                let response = await HttpClient.getAsync(url, false);

                if(response.isSuccessStatusCode)
                {
                    $('#modal-content div:first').html(response.content.readAsString());
                    $('#modal-content div:first').addClass('animated fadeInLeft');
                }else{
                    console.error('Unable to call ' + url + ', status: [' + response.statusCode + ']' + response.statusText);
                    // @ts-ignore
                    toastr.error('Unable to load this page, please try again or contact us on tech@livepick.eu');
                }

                if(afterFinish !== null)
                {
                    afterFinish();
                }
            })();

            return this;
        }
    }
}