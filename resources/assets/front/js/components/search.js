$(function(){
    $('[data-navbar-search]').on('focus', async function(){
        if($(this).val().trim() !== '') {
            await updateSuggestions();
        }
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('[data-navbar-search]').length && !$(event.target).closest('[data-navbar-search-suggestions]').length) {
            hideSuggestions();
        }
    });

    $('[data-navbar-search]').on('input', async function(){
        if($(this).val().trim() === '') {
            hideSuggestions();
            return;
        }

        await updateSuggestions();
    });

    $('[data-navbar-search]').on('keyup', function (e) {
        if (e.key === 'Enter') {
            location.href = `/search/${encodeURIComponent($(this).val())}`;
        }

        if (e.key === 'Escape') {
            hideSuggestions();
        }
    });

    $(document).on('click', '[data-sugg-href]', function(){
        location.href = $(this).data('sugg-href');
    });

    $('.mobile-search-trigger').on('click', function(){
        $('.navbar-logo').fadeOut('fast');
        $('.navbar-orientation').fadeOut('fast');
        $('[data-navbar-orientation]').fadeOut('fast');
        $('.page-dark-search').fadeIn('fast');

        $('html').addClass('lock-scrolling');

        $(this).fadeOut('fast', function(){
            $('.navbar-search-container').fadeIn('fast');
        });
    });

    $('.page-dark-search').on('click', function(){
        $('.navbar-logo').fadeIn('fast');
        $('.navbar-orientation').fadeIn('fast');
        $('[data-navbar-orientation]').fadeIn('fast');
        $('.page-dark-search').fadeOut('fast');

        $('html').removeClass('lock-scrolling');

        $('.mobile-search-trigger').css('display', '');
        $('.navbar-search-container').css('display', '');

    });

    function updateSuggestions() {
        let query = $('[data-navbar-search]').val();
        $('[data-navbar-search-suggestions-content]').css('opacity', '0.8');

        if($.active > 0) {
            try {
                window['searchSuggestionsJQXHR'].abort();
                console.log('calling abort');
            }catch(e) {

            }
        }

        window['searchSuggestionsJQXHR'] = $.ajax({
            type: "POST",
            url: "/search-suggestions.php",
            data: {query},
            success: function(content) {
                if(!content.length) {
                    hideSuggestions();
                    return;
                }

                let html = '';
                content.forEach(function(suggestion){
                    html += `<div class="search-item" data-sugg-href="/search/${encodeURIComponent($(`<p>${suggestion}</p>`).text())}">${suggestion}</div>`;
                });

                console.log(html);

                $('[data-navbar-search-suggestions-content]').css('opacity', '1').html(html);

                if(!$('[data-navbar-search-suggestions]').is(':visible')) {
                    $('[data-navbar-search]').addClass('suggestions-active');
                    $('[data-navbar-search-suggestions]').show();
                }
            }
        });

    }

    function hideSuggestions() {
        if($('[data-navbar-search-suggestions]').is(':visible')) {
            $('[data-navbar-search]').removeClass('suggestions-active');
            $('[data-navbar-search-suggestions]').hide();
        }
    }
});
