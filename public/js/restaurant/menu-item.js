/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

load_data();

function load_data() {
    let restaurantid = $('#maintable').data('restaurantid');
    let hidecolumn = $('#maintable').data('hidecolumn');
    console.log($('#maintable').attr('data-url'));
    
    var table = $('#maintable').DataTable({
        processing : true,
        serverSide : true,
        ajax : {
            url : $('#maintable').attr('data-url'),
            data: {'restaurant_id': restaurantid}
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'categories', name: 'categories' },
            { data: 'unit_price', name: 'unit_price' },
            { data: 'discount_price', name: 'discount_price' },
            { data: 'action', name: 'action' },
        ],
        "ordering" : false
    });
}

