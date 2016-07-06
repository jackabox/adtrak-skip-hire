(function($) {
    "use strict";

    $(function() {

        function getLatLng() {
            var address = $('#ash_postcode').val();

            var geocoder = new google.maps.Geocoder();
            var result = "";

            geocoder.geocode( { 'address': address }, function(results, status) {
                 if (status == google.maps.GeocoderStatus.OK) {
                    var lat = results[0].geometry.location.lat();
                    $('#ash_lat').val(lat);

                    var lng = results[0].geometry.location.lng();
                    $('#ash_lng').val(lng);

                } else {
                    result = "Unable to find address: " + status;
                }

                $('#ash_postcode_form').submit();
            });
        }

        function handle_postcode_submit() {
            if( ! valid_postcode( $('#ash_postcode').val() ) ) {
                $('.ash_postcode_error').html("Please enter a valid postcode.");
                return false;
            } else {
                getLatLng();
            }
        }

        // get the lat/lng on submit form click
        $('#ash_postcode_submit').click(function(event) {
            event.preventDefault();
            handle_postcode_submit();
        });

        // Highjack the enter function to get the lat/lng, then submit
        $('#ash_postcode_form').keydown(function(event) {
            if(event.keyCode == 13) {
                event.preventDefault();
                handle_postcode_submit();
            }
        });
    });

    // get a date 2 days in the future
    if($("#ash_delivery_date").length) {
        var date = new Date();
        date.setDate(date.getDate() + parseInt($("#ash_delivery_date").data('delivery-from')));
        // get the amount of days to deliver
        var days = parseInt($("#ash_delivery_date").data('days'));
        $("#ash_delivery_date").datepicker({
            minDate: date,
            dateFormat: 'dd/mm/yy',
            beforeShowDay: function(date) {
                var day = date.getDay();
                if(days === 5) {
                    return [(day != 0 && day != 6)];
                } else if(days === 6) {
                    return [(day != 0)];
                } else {
                    // slight hack to get it to show all
                    return [(day != 7)];
                }
            }
        });
    }

}(jQuery));

// Helpers
function valid_postcode(postcode) {
    postcode = postcode.replace(/\s/g, "");
    var regex = /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i;
    return regex.test(postcode);
}