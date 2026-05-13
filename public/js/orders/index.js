/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function () {
    // $(".input-daterange").datepicker({
    //     todayBtn: "linked",
    //     format: "dd-mm-yyyy",
    //     autoclose: true,
    // });

    load_data();

    function load_data(startDate = "", endDate = "", orderType = "", code = "", status = "") {
        var table = $("#maintable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $("#maintable").attr("data-url"),
                data: {
                    startDate: startDate,
                    endDate: endDate,
                    orderType: orderType,
                    code: code,
                    status: status,
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "user_id", name: "user_id" },
                { data: "created_at", name: "created_at" },
                { data: "order_type", name: "order_type" },
                { data: "status", name: "status" },
                { data: "total", name: "total" },
                { data: "action", name: "action" },
            ],
            ordering: false,
        });

        let hidecolumn = $("#maintable").data("hidecolumn");
        if (!hidecolumn) {
            table.column(6).visible(false);
        }
    }

    $("#date-search").on("click", function () {
        let startDate = $("#start_date").val();
        let endDate   = $("#end_date").val();
        let orderType = $("#order_type").val();
        let code      = $("#code").val();
        let status    = $("#status").val();
        $("#maintable").DataTable().destroy();
        load_data(startDate, endDate, orderType, code, status);
    });

    $("#refresh").on("click", function () {
        let orderPendingStatus = $("#maintable").attr("data-status");
        $("#start_date").val("");
        $("#end_date").val("");
        $("#order_type").val("");
        $("#code").val("");
        $("#status").val(orderPendingStatus);
        $("#maintable").DataTable().destroy();
        load_data();
    });

    $('#printBtn').on('click', function () {
        let divID = $(this).data('divId');

        $('.dt-length, .dt-search, .dt-info, .dt-paging').hide();
    
        var printContents = document.getElementById(divID).innerHTML;
        var originalContents = document.body.innerHTML;
    
        var printWindow = window.open();

        printWindow.document.open();
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link rel="stylesheet" href="' + window.location.origin + '/backend/css/style.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
            $('.dt-length, .dt-search, .dt-info, .dt-paging').show();
        };
        
    })

});
