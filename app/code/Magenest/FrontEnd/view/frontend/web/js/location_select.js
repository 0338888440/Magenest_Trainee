require([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function($, modal) {
    var options = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
    };
    var popup = modal(options, $('.location_popup'));
    $("#location_select_link").on('click',function(){
        $(".location_popup").modal("openModal");
    });
});