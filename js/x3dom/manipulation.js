//$(document).ready(function() {});

function showLayerManipulation(event) {
	jQuery.ajax({'success':function(data) {
		$("#sidebar #manipulation").html(data);
	},'url':'./index.php?r=tree/getLayerManipulation&id=' + id,'cache':false});return false;
}

function showLeafManipulation(event) {
	jQuery.ajax({'success':function(data) {
		$("#sidebar #manipulation").html(data);
	},'url':'./index.php?r=tree/getLeafManipulation&id=' + id,'cache':false});return false;
}

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