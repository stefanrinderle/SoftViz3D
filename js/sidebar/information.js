function showLayerDetails(event) {
	id = $(event.target).attr('id');
	
	showLayerDetailsById(id);
}

function showLayerDetailsById(id) {
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
			
			setFocus(id);
	   },'url':'./index.php?r=x3dInteraction/getLayerDetails&id=' + id,'cache':false});return false;
}

function showLeafDetails(event) {
	id = $(event.target).attr('id');
	showLeafDetailsById(id);
}

function showLeafDetailsById(id) {
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
			
			setFocus(id);
	   },'url':'./index.php?r=x3dInteraction/getLeafDetails&id=' + id,'cache':false});return false;
}

var selectedId;
var selectedDefaultColour;

function setFocus(id) {
	if (selectedId) {
		setColour(selectedId, selectedDefaultColour);
	}

	element = document.getElementById(id);
	
	var mat = element.getElementsByTagName("Material");
	var aMat = mat[0];
	
	selectedId = id;
	selectedDefaultColour = aMat.getAttribute("diffuseColor");
	
	var isLayer = element.getElementsByTagName("rectangle2d").length;
	if (isLayer) {
		aMat.setAttribute("diffuseColor", "0 0.7 0");
	} else {
		aMat.setAttribute("diffuseColor", "0 0.9 0");
	}
}

function setColour(elementId, color) {
	element = document.getElementById(elementId);
	try {
		var mat = element.getElementsByTagName("Material");
		var aMat = mat[0];
		aMat.setAttribute("diffuseColor", selectedDefaultColour);
	} catch (e) {
	}
}