/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

fetchOrderStatistics(date(-29), date(1));

$("#orderDateRange").on("apply.daterangepicker", function (ev, picker) {
    fetchOrderStatistics(
        picker.startDate.format("YYYY-MM-DD"),
        picker.endDate.format("YYYY-MM-DD")
    );
});

function date(day) {
    let today = new Date();
    today.setDate(today.getDate() + day);
    return today.toISOString().split("T")[0]; // Format as YYYY-MM-DD
}

function fetchOrderStatistics(startDate, endDate) {
    let url = window.location.origin + "/admin/day-wise-order-statistics";
    $.ajax({
        url: url,
        method: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"), // Ensure CSRF protection
            start_date: startDate,
            end_date: endDate,
        },
        success: function (response) {
            let object = JSON.parse(response);

            $("#totalOrders").html(object.total);
            $("#pending").html(object.pending);
            $("#processing").html(object.processing);
            $("#onTheWay").html(object.onTheWay);
            $("#delivered").html(object.delivered);
            $("#canceled").html(object.cancel);
            $("#rejected").html(object.reject);

            $("#totalEarning").html(object.deliveryBoy_totalEarnings);
            $("#totalAcceptedOrders").html(object.deliveryBoy_totalAcceptedOrders);
            $("#completeDelivery").html(object.deliveryBoy_totalCompletedOrders);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        },
    });
}



