function showLayerInformation(event) {
	id = $(event.target).attr('id');
	
	showLayerInformationById(id);
}

function showLayerInformationById(id) {
	jQuery.ajax({'success':function(data) {
			$("#sidebar #information").html(data);
	   },'url':'./index.php?r=tree/getLayerInfo&id=' + id,'cache':false});return false;
}

function showLeafInformation(event) {
	id = $(event.target).attr('id');
	showLeafInformationById(id);
}

function showLeafInformationById(id) {
	jQuery.ajax({'success':function(data) {
			$("#sidebar #information").html(data);
	   },'url':'./index.php?r=tree/getLeafInfo&id=' + id,'cache':false});return false;
}