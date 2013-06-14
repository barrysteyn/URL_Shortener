/* ===================================================
* jquery.fixedscrollingbi-1.0.js
* PUT repo here
* ===================================================
* Copyright 2012 TWAYD, Inc.
*
* ===================================================
*
* Library to fix a menu on screen after a certain scroll point
* ========================================================== */

(function($) {
    $(function() {
	var submitButton = $('#submit-button');
	$('#output').hide();
	
	submitButton.click(function(event) {
		if ($('#error'))
			$('#error').hide();

		event.preventDefault();
		var url = $('#url-input').val();
		if (url) {
			var urlData = {
				"url" : url
			}

			request = $.ajax({
				url: "http://"+host+"/hash",
				type: "post",
				data: urlData,
				success: function(data, textStatus, jqXHR) {
					var hashedUrl = data.result.hashedUrl;
					$('#output').show();
					$('#url-output').html(hashedUrl);
				}
			});
		}		

		return false;
	});
    });
})(jQuery);
