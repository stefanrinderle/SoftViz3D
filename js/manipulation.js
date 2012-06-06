$(document).ready(function() {
	$("#sidebar #manipulation #remove").button().click(function() {
			$("#9").remove();
		}
	);
	
	$("#sidebar #manipulation #add").button().click(function() {
		jQuery.ajax({'success':function(data) {
		      $("#x3dSceneWrapper").append(data);
		   },'url':'./index.php?r=tree/getLayer&id=9','cache':false});return false;});
	
	$("#sidebar #manipulation #test").button().click(function() {
		$("[depth=2]").remove();
	}
);
});

function showLayerInformation(event) {
	console.log("layer");
	test = event.target;
	$("#sidebar #information").text($(test).attr('id'));
}

function showLeafInformation(event) {
	console.log("leaf");
	test = event.target;
	$("#sidebar #information").text($(test).attr('id'));
}