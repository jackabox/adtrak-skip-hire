var pac_input = document.getElementById('aw_autocomplete');

var options = {
	componentRestrictions: {
		country: "uk"
	}
};

// create the autocomplete
var autocomplete = new google.maps.places.Autocomplete(pac_input, options);

// create an event listener on the autocomplete
autocomplete.addListener('place_changed', function () {
	var place = autocomplete.getPlace();	
	document.getElementById('aw_lat').value = place.geometry.location.lat();
	document.getElementById('aw_lng').value = place.geometry.location.lng();
});