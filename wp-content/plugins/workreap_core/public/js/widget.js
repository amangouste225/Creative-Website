
jQuery(document).ready(function(){
	jQuery("select").on("select2:select", function (event) {
		var element 	= event.params.data.element;
		var $ele 		= jQuery(element);

		window.setTimeout(function () {  
			if (jQuery("select").find(":selected").length > 1) {
				var $second_val = jQuery("select").find(":selected").eq(-2);
				$ele.detach();
				$second_val.after($ele);
			} else {
				$ele.detach();
				jQuery("select").prepend($ele);
			}

			jQuery("select").trigger("change");
		}, 1);

	});

	// on unselect, put the selected item last
	jQuery("select").on("select2:unselect", function (event) {
		var element = event.params.data.element;
		jQuery("select").append(element);
	});  
});