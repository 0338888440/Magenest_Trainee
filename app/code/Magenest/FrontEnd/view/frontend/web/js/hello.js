require([
    'jquery',
    'Magento_Ui/js/modal/alert'
], function ($,alert) {
    $(".btn2").on('click',function(){
        alert({
            title: "Test Frontend Modal",
            content: "HelloWorld",
            action: {
                always: function(){}
            }
        });
    })
});