function showLayerDetails(event) {
	id = $(event.target).attr('id');
	
	showLayerDetailsById(id);
}

function showLayerDetailsById(id) {
//	select(id);
	
//	testFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
	   },'url':'./index.php?r=baseX3d/getLayerDetails&id=' + id,'cache':false});return false;
}

function showLeafDetails(event) {
	id = $(event.target).attr('id');
	showLeafDetailsById(id);
}

function showLeafDetailsById(id) {
//	select(id);
	
//	testFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
	   },'url':'./index.php?r=baseX3d/getLeafDetails&id=' + id,'cache':false});return false;
}