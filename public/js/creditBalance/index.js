/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function () {
    $(document).on("change", "#userRoleID", function () {
        let role = $("#userRoleID").val();
        if (role) {
            $.ajax({
                type: "POST",
                url: indexUrl,
                data: { role: role },
                success: function (data) {
                    $("#users").html("0");
                    $("#users").html(data);
                },
            });
        }
    });
});

function printDiv(divID) {
    $('.dt-length, .dt-search, .dt-info, .dt-paging').hide();

    var printContents = document.getElementById(divID).innerHTML;
    var originalContents = document.body.innerHTML;

    var printWindow = window.open('', '', 'width=800,height=600');
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
}

load_data();

$("#date-search").on("click", function () {
    let role_id = $("#userRoleID").val();
    let user_id = $("#users").val();
    $("#maintable").DataTable().destroy();
    load_data(role_id, user_id);
});

$("#clear").on("click", function () {
    $("#userRoleID").val("");
    $("#users").val("");
    $("#maintable").DataTable().destroy();
    load_data();
});

function load_data(role_id = "", user_id = "") {
    var table = $("#maintable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $("#maintable").attr("data-url"),
            data: {
                role_id: role_id,
                user_id: user_id,
            },
        },
        columns: [
            { data: "name", name: "name" },
            { data: "user_role", name: "user_role" },
            { data: "phone", name: "phone" },
            { data: "credit", name: "credit" },
        ],
        ordering: false,
    });
}
