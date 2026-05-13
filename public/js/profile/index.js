/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#previewImage').css({display: 'block'});
            $('#previewImage').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

function initMap() {

    if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition( function(position) {
            getLatLongPosition(position);
        },
        function (error) {
            alert("Location access denied. Using default location.");
            getLatLongPosition({ coords: { latitude: 23.8103, longitude: 90.4125 } }); // Default: Dhaka
        }
    );
    } else {
        alert("Sorry, your browser does not support HTML5 geolocation.");
    }

    function getLatLongPosition(position) {
        let latitude  = position.coords.latitude;
        let longitude = position.coords.longitude;

        const myLatlng = { lat: latitude, lng: longitude };

        const map = new google.maps.Map(document.getElementById("googleMapAddress"), {
            zoom: 15,
            center: myLatlng,
        });

        // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
            content: "Click the map to get latitude & longitude!",
            position: myLatlng,
        });

        infoWindow.open(map);
        var marker;

        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
            // Close the current InfoWindow.
            infoWindow.close();
            // Create a new InfoWindow.
            infoWindow = new google.maps.InfoWindow({
                position: mapsMouseEvent.latLng,
            });

            var latLng = mapsMouseEvent.latLng.toJSON();
            $('#latitude').val(latLng.lat);
            $('#longitude').val(latLng.lng);
            if (marker)
                marker.setMap(null);
            marker = new google.maps.Marker({
                position: myLatlng,
                map,
                draggable:true,
                title: "Your current location.",
            });

            changeMarkerPosition(latLng,marker)
        });

         marker = new google.maps.Marker({
            position: myLatlng,
            map,
             draggable:true,
            title: "Your current location.",
        });
    }

    setTimeout(initEditMap, 100);
}

function initEditMap() {
    const myLatlng = { lat: 23.8103, lng: 90.4125 }; // Default: Dhaka
    const map2Container = document.getElementById("googleMapAddressEdit");

    if (!map2Container) {
        console.error("Edit map container missing!");
        return;
    }

    const map = new google.maps.Map(map2Container, {
        zoom: 15,
        center: myLatlng,
    });

    let marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true,
        title: "Edit Location",
    });

    // Force the map to redraw after it's fully visible
    setTimeout(() => {
        google.maps.event.trigger(map, "resize");
        map.setCenter(myLatlng); // Recenter the map
    }, 500);

    map.addListener("click", (event) => {
        let latLng = event.latLng.toJSON();
        $("#edit_latitude").val(latLng.lat);
        $("#edit_longitude").val(latLng.lng);

        marker.setMap(null);
        marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true,
            title: "New selected location.",
        });
    });
}

function changeMarkerPosition(latLng,marker) {
    var latlng = new google.maps.LatLng(latLng.lat, latLng.lng);
    marker.setPosition(latlng);
}




$("#save-address").on("click",function() {
    let formData = {
        'id'         : $('#id').val(),
        'label'      : $('#label').val(),
        'label_name' : $('#label_name').val(),
        'new_address': $('#new_address').val(),
        'apartment'  : $('#apartment').val(),
        'latitude'   : $('#latitude').val(),
        'longitude'  : $('#longitude').val()
    };

    $.ajax({
        type : 'POST',
        url : setUrl,
        data : formData,
        success : function (data) {
            jQuery('#label').removeClass('is-invalid');
            jQuery('#label_name').removeClass('is-invalid');
            jQuery('#new_address').removeClass('is-invalid');
            jQuery('#apartment').removeClass('is-invalid');
            jQuery('#latitude').removeClass('is-invalid');
            jQuery('#longitude').removeClass('is-invalid');

            let response = JSON.parse(data);
            if(response.errors != 'undefined') {
                jQuery.each(response.errors, function( index, value ) {
                    jQuery('#'+index).addClass('is-invalid');
                });
            }

            if(response.status) {
                location.reload();
            } else {
                iziToast.error({
                    title: 'Error',
                    message: response.message,
                    position: 'topRight'
                });
            }
        }
    });
});

$(".edit-address").on("click",function() {
    let formData = {
        'id'        : $(this).data('id'),
        'label'     : $(this).data('label'),
        'label_name': $(this).data('labelname'),
        'address'   : $(this).data('address'),
        'apartment' : $(this).data('apartment'),
        'latitude'  : $(this).data('latitude'),
        'longitude' : $(this).data('longitude')
    };
    
    if (formData.label === 15) {
        $('#edit_other_label').removeClass('hidden');        
    } else {
        $('#edit_other_label').addClass('hidden');        
    }

    jQuery('#edit_id').val(formData.id);
    jQuery('#edit_label').val(formData.label);
    jQuery('#edit_label_name').val(formData.label_name);
    jQuery('#edit_new_address').val(formData.address);
    jQuery('#edit_apartment').val(formData.apartment);
    jQuery('#edit_latitude').val(formData.latitude);
    jQuery('#edit_longitude').val(formData.longitude);
});

function saveEditAddress () {
    let formData = {
        'id'         : $('#edit_id').val(),
        'label'      : $('#edit_label').val(),
        'label_name' : $('#edit_label_name').val(),
        'new_address': $('#edit_new_address').val(),
        'apartment'  : $('#edit_apartment').val(),
        'latitude'   : $('#edit_latitude').val(),
        'longitude'  : $('#edit_longitude').val()
    };

    $.ajax({
        type : 'POST',
        url : setUrl,
        data : formData,
        success : function (data) {
            jQuery('#edit_label').removeClass('is-invalid');
            jQuery('#edi_label_name').removeClass('is-invalid');
            jQuery('#edit_new_address').removeClass('is-invalid');
            jQuery('#edit_apartment').removeClass('is-invalid');
            jQuery('#edit_latitude').removeClass('is-invalid');
            jQuery('#edit_longitude').removeClass('is-invalid');

            let response = JSON.parse(data);
            if(response.errors != 'undefined') {
                jQuery.each(response.errors, function( index, value ) {
                    jQuery('#'+index).addClass('is-invalid');
                });
            }

            if(response.status) {
                location.reload();
            } else {
                iziToast.error({
                    title: 'Error',
                    message: response.message,
                    position: 'topRight'
                });
            }
        }
    });
}

$(".resetForm").on("click",function() {
    let formData = {
        'id': '',
        'label': 5,
        'street': '',
        'note': '',
        'latitude': '',
        'longitude': ''
    };

    jQuery('#id').val(formData.id);
    jQuery('#label').val(formData.label);
    jQuery('#street').val(formData.street);
    jQuery('#note').val(formData.note);
    jQuery('#latitude').val(formData.latitude);
    jQuery('#longitude').val(formData.longitude);
});

function editselect () {
    let val = $('#edit_label').val();
    if (val == 15){
        $('#edit_other_label').removeClass('hidden');
    } else {
        $('#edit_other_label').addClass('hidden');
    } 
}
function select () {
    let val = $('#label').val();
    if (val == 15){
        $('#other_label').removeClass('hidden');
    } else {
        $('#other_label').addClass('hidden');
    } 
}
