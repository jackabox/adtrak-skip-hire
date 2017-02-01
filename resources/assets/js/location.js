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

    if ($('#ash_autocomplete').length) {
        var pac_input = document.getElementById('ash_autocomplete');

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
            document.getElementById('ash_lat').value = place.geometry.location.lat();
            document.getElementById('ash_lng').value = place.geometry.location.lng();
	        $('#ash_submit').removeAttr('disabled');
        });

        pacSelectFirst(pac_input);

        // on focusin, trigger the select first
        $(function() {
            $('#ash_autocomplete').focusin(function() {
                $(window).keydown(function(event) {
                    if (event.keyCode == 13 || event.keyCode == 9) {
                        event.preventDefault();
                        pacSelectFirst(pac_input);
                    }

                    if (event.keyCode == 13) {
						$('#ash_submit').removeAttr('disabled');
                        $('#ash_lookup').submit();
                    }
                });
            });
        });
    }

    $(function() {
        $('.ash-form').parsley().on('field:validated', function() {
            var ok = $('.parsley-error').length === 0;
            $('.as-notification-warning').toggleClass('hidden', ok);
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
    });
} (jQuery));