require([
    'jquery'
], function ($) {
    var countRoom = $.ajax({
        type: "POST",
        url: BASE_URL + "cybergame/account/countroom",
        async: false,
        dataType: "json",
        complete: function (response) {
        },
        error: function (response) {
        }
    }).responseJSON;
    countRoom = JSON.parse(countRoom);
    var html = 'Update Room Info (' + countRoom + ')';
    $('strong').each(function () {
        if ($(this).text() == 'Update Room Info') {
            $(this).html(html);
        }
    });
    $('a').each(function () {
        if ($(this).text() == 'Update Room Info') {
            $(this).html(html);
        }
    });
});