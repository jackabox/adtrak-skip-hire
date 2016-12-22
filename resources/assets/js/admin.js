jQuery(document).ready(function ($) {

	$('.ash-skip-delete').click(function () {
		var truly = confirm('Are you sure you want to delete this skip?'),
			id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			redirect = $(this).data('redirect'),
			btn = $(this);

		if (truly === true) {
			$.ajax({
				type: 'post',
				url: SHajax.ajaxurl,
				data: {
					action: 'ash_skip_delete',
					nonce: nonce,
					id: id
				},
				success: function (result) {
					if (result === "error") {
						notification.removeClass('success')
							.addClass('error')
							.html("Sorry, an error occured with deleting the skip. Please try again.")
							.show(500);
					} else if (result === "success") {
						window.location.replace(redirect);
					}
				}
			});
		}

		return false;
	});

	$('.ash-permit-delete').click(function () {
		var truly = confirm('Are you sure you want to delete this permit?'),
			id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			redirect = $(this).data('redirect'),
			btn = $(this);

		if (truly === true) {
			$.ajax({
				type: 'post',
				url: SHajax.ajaxurl,
				data: {
					action: 'ash_permit_delete',
					nonce: nonce,
					id: id
				},
				success: function (result) {
					if (result === "error") {
						notification.removeClass('success')
							.addClass('error')
							.html("Sorry, an error occured with deleting the permit. Please try again.")
							.show(500);
					} else if (result === "success") {
						window.location.replace(redirect);
					}
				}
			});
		}

		return false;
	});

	$('.ash-coupon-delete').click(function () {
		var truly = confirm('Are you sure you want to delete this coupon?'),
			id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			redirect = $(this).data('redirect'),
			btn = $(this);

		if (truly === true) {
			$.ajax({
				type: 'post',
				url: SHajax.ajaxurl,
				data: {
					action: 'ash_coupon_delete',
					nonce: nonce,
					id: id
				},
				success: function (result) {
					if (result === "error") {
						notification.removeClass('success')
							.addClass('error')
							.html("Sorry, an error occured with deleting the coupon. Please try again.")
							.show(500);
					} else if (result === "success") {
						window.location.replace(redirect);
					}
				}
			});
		}

		return false;
	});

	$('.ash-location-delete').click(function () {
		var truly = confirm('Are you sure you want to delete this location?'),
			id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			redirect = $(this).data('redirect'),
			btn = $(this);

		if (truly === true) {
			$.ajax({
				type: 'post',
				url: SHajax.ajaxurl,
				data: {
					action: 'ash_location_delete',
					nonce: nonce,
					id: id
				},
				success: function (result) {
					if (result === "error") {
						notification.removeClass('success')
							.addClass('error')
							.html("Sorry, an error occured with deleting the location. Please try again.")
							.show(500);
					} else if (result === "success") {
						window.location.replace(redirect);
					}
				}
			});
		}

		return false;
	});

	$(function () {
		var $lat = 52.9539559,
			$lng = -1.1543077999999696;

		if ($('#as_lat').val() !== '') {
			$lat = Number($('#as_lat').val());
		}

		if ($('#as_lng').val() !== '') {
			$lng = Number($('#as_lng').val());
		}

		if ($('#as_location').length) {
			var pac_input = document.getElementById('as_location');

			var map = new google.maps.Map(document.getElementById('map'), {
				center: { lat: $lat, lng: $lng },
				zoom: 13
			});

			var options = {
				componentRestrictions: {
					country: "uk"
				}
			};

			// create the autocomplete
			var autocomplete = new google.maps.places.Autocomplete(pac_input, options);
			var infowindow = new google.maps.InfoWindow();
			var marker = new google.maps.Marker({
				map: map,
				position: { lat: $lat, lng: $lng },
				anchorPoint: new google.maps.Point(0, -29)
			});
			marker.setVisible(true);

			// create an event listener on the autocomplete
			autocomplete.addListener('place_changed', function () {
				var place = autocomplete.getPlace();

				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
					map.fitBounds(place.geometry.viewport);
				} else {
					map.setCenter(place.geometry.location);
					map.setZoom(17);  // Why 17? Because it looks good.
				}
				marker.setPosition(place.geometry.location);
				marker.setVisible(true);

				var address = '';
				if (place.address_components) {
					address = [
						(place.address_components[0] && place.address_components[0].short_name || ''),
						(place.address_components[1] && place.address_components[1].short_name || ''),
						(place.address_components[2] && place.address_components[2].short_name || '')
					].join(' ');
				}

				infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
				infowindow.open(map, marker);

				// set the lat / lng of the button dependant on if lat / lng exist
				document.getElementById('as_lat').value = place.geometry.location.lat();
				document.getElementById('as_lng').value = place.geometry.location.lng();
			});
		}
	});
});
