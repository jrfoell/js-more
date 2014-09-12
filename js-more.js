var jsMoreElements;

(function($) {

jsMoreElements = {

	init : function() {
		this.attachListeners();
	},
	
	attachListeners : function() {
		var that = this;
		
		$('[id^="show-more-"]').click(function() {
			var id = $(this).attr('id').substring(10);
			// get full article for statistical / reporting purposes
			// (showing the user viewed the full article)
			$.get($(this).attr('href'), function(data) {
				//no action needed
			});
			$(this).hide();
			$('#more-' + id).show();
			
			//don't actually go to address
			return false;
		});

		$('[id^="show-less-"]').click(function() {
			var id = $(this).attr('id').substring(10);
			$('#more-' + id).hide();
			$('#show-more-' + id).show();

			//scroll back to the summary article
			var post = document.getElementById('post-' + id);
			if(post) post.scrollIntoView(false);

			//don't refresh after click
			return false;
		});
	},

};

$(document).ready(function($){ jsMoreElements.init(); });

})(jQuery);
