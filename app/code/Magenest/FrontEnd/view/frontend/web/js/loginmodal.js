require([
        'jquery',
        'Magento_Ui/js/modal/modal'
    ], function($, modal) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            };
        var popup = modal(options, $('.popup-modal'));
        $(".btn3").on('click',function(){
            $(".popup-modal").modal("openModal");
            });
    });