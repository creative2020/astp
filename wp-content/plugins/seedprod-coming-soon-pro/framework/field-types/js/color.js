// For Color Field Type

// jQuery(document).ready(function($){
//     // Color Picker
//     $('.pickcolor').click( function(e) {
// 		colorPicker = jQuery(this).next('div');
// 		input = jQuery(this).prev('input');
// 		if($(input).val() == ''){
// 			$(input).val('#ffffff');
// 		}
// 		$(colorPicker).farbtastic(input);
// 		colorPicker.show();
// 		e.preventDefault();
// 		$(document).mousedown( function() {
//     		$(colorPicker).hide();
//     	});
// 	});
// });

jQuery(document).ready(function($){
    $('.pickcolor-field').wpColorPicker();
});

