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
			radius = $('#aw_radius').val();

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
				console.log(result);
			}
		});
		return false;
	});
});
