$(document).ready(
		function() {

			var maskHeight = $(document).height();
			var maskWidth = $(window).width();

			$('#mask').css( {
				'width' : maskWidth,
				'height' : maskHeight
			});

			$('#mask').fadeIn(1000);
			$('#mask').fadeTo("slow", 0.6);

			// Get the window height and width
			var winH = $(window).height();
			var winW = $(window).width();
			
			$('#dialog2').css('top', 700 / 2 - $('#dialog2').height() / 2);
			$('#dialog2').css('left', 1293 / 2 - $('#dialog2').width() / 2);

			$('#dialog2').fadeIn(2000);

			$('.window .close').click(function(e) {
				e.preventDefault();

				$('#mask').hide();
				$('.window').hide();
			});
			
			
			$(function() {
				$( ".datepicker" ).datepicker({
					showOn: "button",
					buttonImage: "../public/images/calendario.png",
					buttonImageOnly: true,
					buttonText: "Selecione uma data."
				});
			});
		 });