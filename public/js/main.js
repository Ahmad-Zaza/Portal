$(function() {
    $('a[href^="' + location.href + '"]').addClass('active');
    var row = $('a.sub-menu-link[href$="' + location.href + '"]').closest('.row')
    row.find('.left-nav-list').addClass('active').removeClass('collapsed');
    row.find('.submenu').removeClass('collapse in');
})