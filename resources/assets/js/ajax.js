jQuery(document).ready(function ($) 
{
	$('.adwi-edit-location').click(function () 
	{
		var id = $(this).data('id'),
			nonce = $(this).data('nonce'),
			btn = $(this),
			name = $('#aw_name').val(),
			desc = $('#aw_description').val(),
			phone = $('#aw_phone').val(),
			location = $('#aw_location').val(),
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

	$('.adwi-delete-location').click(function () 
	{
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
});
