$(document).ready(function() {
	$("input[type=text][class!=senha]").blur(function() {
		if ($(this).val() == "") {
			$(this).val($("label[for=" + this.id + "]").text());
		}
	});
	
	
	$("input[type=text][class!=senha]").focus(function() {
		if ($(this).val() == $("label[for=" + this.id + "]").text()) {
			$(this).val("");
		}
	});
	
	
	$(".senha[type=text]").focus(function() {
		$(this).css({
			display : "none"
		});
		$("input[name=" + this.id + "]").css({
			display : "block"
		});
		$("input[name=" + this.id + "]").val("");
		$("input[name=" + this.id + "]").focus();
	});
	
	
	$(".senha[type=password]").blur(function() {
		if ($(this).val() == "") {
			$("#" + $(this).attr("name")).css({
				display : "block"
			});
			$(this).css({
				display : "none"
			});
		}
	});

});