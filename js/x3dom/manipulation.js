function layerClickedEvent(event) {
	showLayerDetails(event);
}

function leafClickedEvent(event) {
	showLeafDetails(event);
}

function nodeClickedEvent(event) {
	var orig_id = $(event.target).attr('id');
	var id = orig_id.split("_");
	
	showLayerDetailsById(id[1]);
}

function edgeClickedEvent(event) {
	console.log(event);
}