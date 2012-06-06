var zoomed = false;

var offset = 600;
function resize() {
	// WIDTH
	left_margin = $(".container").css("margin-left").substring(0, $(".container").css("margin-left").length - 2);
	right_margin = $(".container").css("margin-left").substring(0, $(".container").css("margin-left").length - 2);
	content_padding = $("#content").css("padding").substring(0, $("#content").css("padding").length - 2);
	
	sidebar_width = $("#sidebar").css("width").substring(0, $("#sidebar").css("width").length - 2)
	
	calculatedSize = window.innerWidth - left_margin - right_margin - (content_padding * 2) - (content_padding * 2) - sidebar_width;
	
	if (calculatedSize < 580) {
		calculatedSize = 580;
	}
	
	var x3d_element = document.getElementById('the_x3delement');
	x3d_element.style.width  = calculatedSize + 'px';
	
	//HEIGHT
	x3d_element.style.height = window.innerHeight - 250;
}

$(document).ready(function() {
	resize();
});

$(window).resize(function() {
	resize();
});

function toggle_size(button) {
	//vorlage ist:
	//funktioniert aber mit meinem template nicht
//	var body = document.getElementById('main');
//	var title = document.getElementById('title');
//	var x3d_element = document.getElementById('the-element');
//	
//	var new_width_css = "100%";
//
//	if (zoomed) {
//		body.style.padding = "10px";
//		title.style.display = "block";
//		button.style.backgroundColor = "#202021";
//        x3d_element.style.borderWidth  = "1px"
//		new_width = "50%";
//	} else {
//		body.style.padding = "0";
//		title.style.display = "none";
//		button.style.backgroundColor = "#c23621";
//        x3d_element.style.borderWidth  = "0"
//		new_width = "100%";
//	}
//
//	zoomed = !zoomed;
//    
//    x3d_element.style.width  = new_width
//    x3d_element.style.height = new_width
    return true;
}