"use strict";

$(function () {
    var userID = $("#user_id").val();
    if (userID) {

        $.ajax({
            type: "POST",
            url: $("#user_id").data("url"),
            data: { user_id: userID },
            dataType: "html",
            success: function (data) {
                $("#userInfo").html(data);
            },
        });
    }
});

$(document).on("change", "#user_id", function () {
    $.ajax({
        type: "POST",
        url: $(this).data("url"),
        data: { user_id: $(this).val() },
        dataType: "html",
        success: function (data) {
            $("#userInfo").html(data);
        },
    });
});
