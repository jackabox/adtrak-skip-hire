(function($) {
    "use strict";

    function pacSelectFirst(input) {
        var _addEventListener = (input.addEventListener) ? input.addEventListener : input.attachEvent;

        function addEventListenerWrapper(type, listener) {
            if (type == "keydown") {
                var orig_listener = listener;

                listener = function(event) {
                    var suggestion_selected = $(".pac-item-selected").length > 0;

                    if ((event.which == 13 || event.which == 9) && !suggestion_selected) {
                        var simulated_downarrow = $.Event("keydown", { keyCode: 40, which: 40 })
                        orig_listener.apply(input, [simulated_downarrow]);
                    }

                    orig_listener.apply(input, [event]);
                };
            }

            _addEventListener.apply(input, [type, listener]);
        }

        if (input.addEventListener) {
            input.addEventListener = addEventListenerWrapper;
        } else if (input.attachEvent) {
            input.attachEvent = addEventListenerWrapper;
        }
    }	

    if ($('#as_autocomplete').length) {
        var pac_input = document.getElementById('as_autocomplete');

        var options = {
            componentRestrictions: {
                country: "uk"
            }
        };

        // create the autocomplete
        var autocomplete = new google.maps.places.Autocomplete(pac_input, options);

        // create an event listener on the autocomplete
        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            document.getElementById('as_lat').value = place.geometry.location.lat();
            document.getElementById('as_lng').value = place.geometry.location.lng();
	        $('#as_submit').removeAttr('disabled');
        });

        pacSelectFirst(pac_input);

        // on focusin, trigger the select first
        $(function() {
            $('#as_autocomplete').focusin(function() {
                $(window).keydown(function(event) {
                    if (event.keyCode == 13 || event.keyCode == 9) {
                        event.preventDefault();
                        pacSelectFirst(pac_input);
                    }

                    if (event.keyCode == 13) {
						$('#as_submit').removeAttr('disabled');
                        $('#as_lookup').submit();
                    }
                });
            });
        });
    }

    if ($('#as_map').length) {
        $(function() {
            var $lat = $('#as_map').data('lat'),
                $lng = $('#as_map').data('lng');

            var map = new google.maps.Map(document.getElementById('as_map'), {
                center: { lat: $lat, lng: $lng },
                zoom: 13
            });

            var marker = new google.maps.Marker({
                position: { lat: $lat, lng: $lng },
                map: map
            });

        });
    }
} (jQuery));