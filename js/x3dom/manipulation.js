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

function removeLayerById(id) {
	jQuery.ajax({'success':function(data) {
			$("#" + id).remove();
			
			var json = eval('('+data+')');
		 	$.each(json, function(key, value) {
		 		$("#" + value).remove();
		 	});
	   },'url':'./index.php?r=tree/getLayerChildren&id=' + id,'cache':false});
}

function showLayerById(id) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	   },'url':'./index.php?r=tree/getLayer&id=' + id,'cache':false});
}