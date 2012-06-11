$(document).ready(function() {
		resize();
		
		$( "#sidebar" ).accordion({
			collapsible: true
		});
});

function layerClickedEvent(event) {
	active = getActiveAccordianTab();
	
	if (active == 0) {
		showLayerInformation(event);
	} else if (active == 1) {
		showLayerManipulation(event);
	}
}

function leafClickedEvent(event) {
	active = getActiveAccordianTab();
	
	if (active == 0) {
		showLeafInformation(event);
	} else if (active == 1) {
		showLeafManipulation(event);
	}
}

function getActiveAccordianTab() {
	/*  0 => information
	 *  1 => manipulation
	 *  2 => navigation
	 */
	return $("#sidebar").accordion( "option", "active" );
}

$(window).resize(function() {
	resize();
});


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