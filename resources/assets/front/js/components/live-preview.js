$(function(){
    if(window['isDesktopDevice']) {
        $(document).on('mouseenter', '[data-live-preview]', function(){
            videoLivePreviewStart(this);
        }).on('mouseleave', '[data-live-preview]', function(){
            videoLivePreviewStop(this);
        });

        $(document).on('mouseenter', '[data-live-thumbs]', function(){
            videoThumbsPreviewStart(this);
        }).on('mouseleave', '[data-live-thumbs]', function(){
            videoThumbsPreviewStop(this);
        });
    }


    if(!window['isDesktopDevice']) {
        var activeTouchElement = null;
        var activeTouchElementPlaying = false;

        $(document).on('touchstart', '[data-live-preview]', function(evt){
            if(activeTouchElementPlaying && !activeTouchElement.is($(this))) {
                if(typeof activeTouchElement.data('live-preview') !== 'undefined') {
                    videoLivePreviewStop(activeTouchElement);
                }else{
                    videoThumbsPreviewStop(activeTouchElement);
                }

                activeTouchElementPlaying = false;
            }

            activeTouchElement = $(this);
        });

        $(document).on('touchmove', '[data-live-preview]', function(evt){
            if(activeTouchElementPlaying || activeTouchElement == null) {
                return;
            }

            activeTouchElementPlaying = true;

            if(typeof activeTouchElement.data('live-preview') !== 'undefined') {
                videoLivePreviewStart(activeTouchElement);
            }else{
                videoThumbsPreviewStart(activeTouchElement);
            }
        });
    }
});


function videoLivePreviewStart(element) {
    let videoId = $(element).data('video-id');
    let videoPreviewUrl = $(element).data('live-preview');
    let thumbImage = $(element).find('img[data-video-thumb]');

    $(element).find('[data-video-spinner]').show();
    $(element).find('[data-video-preview-container]').html('<video style="width:'+thumbImage.width()+'px;height:'+thumbImage.height()+'px" src="'+videoPreviewUrl+'" autoplay playsinline muted loop disableremoteplayback></video>');

    let _this = element;

    document.querySelector("[data-video-id='"+videoId+"'] video").oncanplay = function() {
        if($("[data-video-id='"+videoId+"'] video").length) {
            $(_this).find('[data-video-spinner]').hide();
            $(_this).find('[data-thumb-shadow]').hide();
            $(_this).find('[data-video-thumb]').hide();
            $(_this).find('[data-video-preview-container]').show();
            $(_this).css('transform', 'scale(1.05)');

        }
    };
}

function videoLivePreviewStop(element) {
    if(! $('[data-video-preview-container]').is(':visible')) {
        return;
    }

    $(element).find('[data-video-preview-container]').html('');
    $(element).find('[data-video-preview-container]').hide();
    $(element).find('[data-video-spinner]').hide();
    $(element).find('[data-thumb-shadow]').show();
    $(element).find('[data-video-thumb]').show();
    $(element).css('transform', '');
}

var activeThumbsPreviews = [];
function videoThumbsPreviewStart(element) {
    let videoId = $(element).data('video-id');
    let thumbs = $(element).data('live-thumbs').toString().split(',');

    let uniquePointer = performance.now();
    activeThumbsPreviews[videoId] = uniquePointer;

    let thumbsLoadedCount = 0;
    let _this = element;

    $(element).find('[data-video-spinner]').show();

    thumbs.forEach(function(src) {
        let image = new Image();
        image.onload = async function() {
            thumbsLoadedCount++;

            if(thumbsLoadedCount === thumbs.length && activeThumbsPreviews[videoId] === uniquePointer) {
                $(_this).find('[data-video-preview-container]').html('<img src="'+$(_this).find('[data-video-thumb]').attr('src')+'">');

                $(_this).find('[data-video-spinner]').hide();
                $(_this).find('[data-thumb-shadow]').hide();
                $(_this).find('[data-video-thumb]').hide();
                $(_this).find('[data-video-preview-container]').show();
                $(_this).css('transform', 'scale(1.05)');

                infinite_loop:
                    while(true) {
                        for(let thumbIndex in thumbs) {
                            let e = $("[data-video-id='"+videoId+"'] [data-video-preview-container] img");
                            if(!e.length || activeThumbsPreviews[videoId] !== uniquePointer) {
                                break infinite_loop;
                            }

                            e.attr('src', thumbs[thumbIndex]);
                            await Thread.sleep(700);
                        }
                    }

            }
        };

        image.src = src;
    });
}

function videoThumbsPreviewStop(element) {
    let videoId = $(element).data('video-id');
    activeThumbsPreviews[videoId] = null;

    $(element).find('[data-video-preview-container]').html('');
    $(element).find('[data-video-preview-container]').hide();
    $(element).find('[data-video-spinner]').hide();
    $(element).find('[data-thumb-shadow]').show();
    $(element).find('[data-video-thumb]').show();
    $(element).css('transform', '');
}
