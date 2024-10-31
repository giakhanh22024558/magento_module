require(['Magento_Customer/js/customer-data'], function (customerData) {
    var customSection = customerData.get('custom_section');
    customSection.subscribe(function (data) {
        // data.custom_section_data contains your table data
        console.log(data.custom_section_data);
        
    });
});

console.log('call');

// define([
//     'jquery',
//     'Magento_Customer/js/customer-data'
// ], function ($, customerData) {
//     'use strict';

//     return function (config, element) {
//         var customSection = customerData.get('custom_section');

//         customSection.subscribe(function (data) {
//             if (data.occasions) {
//                 var occasionsHtml = '';
//                 $.each(data.occasions, function (index, occasion) {
//                     occasionsHtml += '<tr><td>' + occasion.name + '</td><td>' + occasion.date + '</td></tr>';
//                 });
//                 $(element).find('tbody').html(occasionsHtml);
//             }
//         });

//         // Initial load
//         if (customSection().occasions) {
//             var occasionsHtml = '';
//             $.each(customSection().occasions, function (index, occasion) {
//                 occasionsHtml += '<tr><td>' + occasion.name + '</td><td>' + occasion.date + '</td></tr>';
//             });
//             $(element).find('tbody').html(occasionsHtml);
//         }
//     };
// });