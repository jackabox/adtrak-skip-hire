jQuery(document).ready(function ($) {


	$('.adwi-edit-location').click(function () {
		var id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			btn = $(this),
			name = $('#aw_name').val(),
			desc = $('#aw_description').val(),
			phone = $('#aw_number').val(),
			location = $('#aw_location').val(),
			lat = $('#aw_lat').val(),
			lng = $('#aw_lng').val(),
			radius = $('#aw_radius').val(),
			notification = $('.aw-notification');

		$.ajax({
			type: 'post',
			url: WSAjax.ajaxurl,
			data: {
				action: 'windscreen_edit_location',
				nonce: nonce,
				id: id,
				name: name,
				desc: desc,
				phone: phone,
				location: location,
				lat: lat, 
				lng: lng,
				radius: radius
			},
			success: function (result) {
				if (result === "error") {
					notification.removeClass('success')
						.addClass('error')
						.html("Sorry, an error occured with saving the location. Please try again.")
						.show(500);
				} else if (result === "success") {
					notification.removeClass('error')
						.addClass('success')
						.html("Location '" + name + "' has been updated successfully.")
						.show(500);
				}
			}
		});
		return false;
	});

	$('.adwi-delete-location').click(function () {
		var truly = confirm('Are you sure you want to delete this location?'),
			id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			redirect = $(this).data('redirect'),
			btn = $(this);

		if (truly === true) {
			$.ajax({
				type: 'post',
				url: WSAjax.ajaxurl,
				data: {
					action: 'windscreen_delete_location',
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
		var pac_input = document.getElementById('aw_location');

		var options = {
			componentRestrictions: {
				country: "uk"
			}
		};

		// create the autocomplete
		var autocomplete = new google.maps.places.Autocomplete(pac_input, options);

		// create an event listener on the autocomplete
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place = autocomplete.getPlace();

			// set the lat / lng of the button dependant on if lat / lng exist
			document.getElementById('aw_lat').value = place.geometry.location.lat();
			document.getElementById('aw_lng').value = place.geometry.location.lng();
		});
	});
});
