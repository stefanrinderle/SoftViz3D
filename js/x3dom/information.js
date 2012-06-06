//
//var selectedId;
//var selectedColor = "0 0 0.8";
//var saveDefaultColor;
//
//function select(id) {
//	if (id != selectedId) {
//		// first deselect old selection
//		if (selectedId) {
//			$("#" + selectedId).find("Material").attr("diffuseColor", saveDefaultColor);
//		}
//		
//		selectedId = id;
//		saveDefaultColor = $("#" + id).find("Material").attr("diffuseColor");
//		
//		$("#" + selectedId).find("Material").attr("diffuseColor", selectedColor);
//	}
//}

function testFocus(id) {
	var configure = document.getElementById('the_x3delement');
	configure.runtime.showObject(document.getElementById(id));
}

function showLayerInformation(event) {
	id = $(event.target).attr('id');
	
	showLayerInformationById(id);
}

function showLayerInformationById(id) {
//	select(id);
	
//	testFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #information").html(data);
	   },'url':'./index.php?r=tree/getLayerInfo&id=' + id,'cache':false});return false;
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
	   },'url':'./index.php?r=tree/getLeafInfo&id=' + id,'cache':false});return false;
}