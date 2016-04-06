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

        // get the lat/lng on submit form click
        $('#ash_postcode_submit').click(function() {
            event.preventDefault();
            getLatLng();
        });

        // Highjack the enter function to get the lat/lng, then submit
        $('#ash_postcode_form').keydown(function(event) {
            if(event.keyCode == 13) {
                event.preventDefault();
                getLatLng();
            }
        });

    });

}(jQuery));

