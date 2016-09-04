$(function () {
    'use strict';

    var buttons = $('.btn');
    var last, first;

    buttons.on('click', function () {
        first = $('button:first-of-type');
        last = $('button:last-of-type');
        last.after(first);
    });
});