$(document).ready(function() {
	$("#manipulation #remove").button().click(function() {
			$("#9").remove();
		}
	);
	
	$("#manipulation #add").button().click(function() {
		jQuery.ajax({'success':function(data) {
		      $("#x3dSceneWrapper").append(data);
		   },'url':'./index.php?r=tree/getLayer&id=9','cache':false});return false;});
});