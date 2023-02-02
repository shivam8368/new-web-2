$(function(){
    $('[data-category-search]').on('input', function(){
        let searchPhrase = $(this).val().toLowerCase();

        $('[data-category-search-item]').each(function(){
            let categoryName = String($(this).data('category-search-item'));

            if(categoryName.indexOf(searchPhrase) !== -1 || categoryName === '') {
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
});
