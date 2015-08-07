/**
 * 
 */

$(document).ready(function() {
	$('input[type=checkbox]').click(function () {
		if (this.checked) {
			tr = $(this).parent().parent();
			$(tr).addClass("selectedIndex");
		} else {
			tr = $(this).parent().parent();
			$(tr).removeClass("selectedIndex");
		}
	});
});