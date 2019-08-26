require([
        "jquery",
        'Magento_Ui/js/modal/alert'
    ], function($,alert){
        "use strict";

        $(document).ready(function() {

            $('#save').on('click',function(e) {
                var firstNameVal = $('#first-name-text').val();
                var lastNameVal = $('#last-name-text').val();
                $.ajax({
                    type: "POST",
                    url: "http://localhost/m-228/knockout/test/savecustomer",
                    data: {firstname: firstNameVal, lastname: lastNameVal},
                    success: function (data) {
                        alert({
                            title: "Save Name Customer",
                            content: "Success",
                            action: {
                                always: function(){}
                            }
                        });
                        console.log("ys");
                    },
                    error: function () {
                        console.log("no");
                    }
                });
            });
        });

    }
);
// require([
//     'jquery',
//     'Magento_Ui/js/modal/alert'
// ], function ($,alert) {
//     $("#save").on('click',function(){
//         alert({
//             title: "Test Frontend Modal",
//             content: "HelloWorld",
//             action: {
//                 always: function(){}
//             }
//         });
//     })
// });