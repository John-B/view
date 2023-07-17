(function ($) {
    var menuItem = $('.single-grant #header li.menu-item-2239');
    $(menuItem).addClass('current-menu-item');
})(jQuery);

(function ($) {
    var searchClear = $('.search-results #search-input');
    searchClear.attr('value', '');
})(jQuery);

(function ($) {
    $( "#addthis" ).click(function() {     
       $('#social-icons').slideToggle("400", "swing");
    });
})(jQuery);
