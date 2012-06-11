function showLayerInformation(event) {
	id = $(event.target).attr('id');
	
	showLayerInformationById(id);
}

function showLayerInformationById(id) {
//	select(id);
	
//	testFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #information").html(data);
	   },'url':'./index.php?r=baseX3d/getLayerInfo&id=' + id,'cache':false});return false;
}

function showLeafInformation(event) {
	id = $(event.target).attr('id');
	showLeafInformationById(id);
}

function showLeafInformationById(id) {
//	select(id);
	
//	testFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #information").html(data);
	   },'url':'./index.php?r=baseX3d/getLeafInfo&id=' + id,'cache':false});return false;
}