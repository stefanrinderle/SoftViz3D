function showLayerDetails(event) {
	id = $(event.target).attr('id');
	
	showLayerDetailsById(id);
}

function showLayerDetailsById(id) {
	setFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
	   },'url':'./index.php?r=baseX3d/getLayerDetails&id=' + id,'cache':false});return false;
}

function showLeafDetails(event) {
	id = $(event.target).attr('id');
	showLeafDetailsById(id);
}

function showLeafDetailsById(id) {
	setFocus(id);
	
	jQuery.ajax({'success':function(data) {
			$("#sidebar #details").html(data);
	   },'url':'./index.php?r=baseX3d/getLeafDetails&id=' + id,'cache':false});return false;
}

var selectedId;
var selectedDefaultColour;

function setFocus(id)
{
	if (selectedId) {
		setColour(selectedId, selectedDefaultColour);
	}

	element = document.getElementById(id);
	var mat = element.getElementsByTagName("Material");
	var aMat = mat[0];
	
	selectedId = id;
	selectedDefaultColour = aMat.getAttribute("diffuseColor");
	
	aMat.setAttribute("diffuseColor", "0 0 1");
}

function setColour(elementId, color) {
	element = document.getElementById(elementId);
	var mat = element.getElementsByTagName("Material");
	var aMat = mat[0];
	aMat.setAttribute("diffuseColor", selectedDefaultColour);
}