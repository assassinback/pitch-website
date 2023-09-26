$(document).ready(function () {
	$('a.confirm').click(function(e) {
		if ( confirm( $(this).attr('title') ) ) {
			return true;
		}
		else {
			e.preventDefault();
		}
	});
	$('a.error').click(function(e) {
		if ( alert( $(this).attr('title') ) ) {
			return false;
		}else{
			return false;
		}
	});
});