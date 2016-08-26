function showFilter(el, selector) {
    $('.filter-extra').each(function() {
        $(this).slideUp('fast');
    });

    var element = $(el);
    if (element.hasClass('filter-on')) {
        element.removeClass('filter-on');
    } else {
        $('.filter-all').each(function() {
            $(this).removeClass('filter-on');
        });
        element.addClass('filter-on');
        $(selector).slideDown('fast');
    }
}
