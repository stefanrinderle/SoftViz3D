// REMOVE LAYER
function leafRemoveLayerById(layerId) {
	removeLayerById(layerId, function() {
		showLayerDetailsById(layerId);
	});
}

function layerRemoveLayerById(layerId, parentId) {
	removeLayerById(layerId, function () {
		showLayerDetailsById(parentId);
	});
}

function removeLayerById(id, callback) {
	jQuery.ajax({'success':function(data) {
			// remove layer
			$("#" + id).remove();
			
			// remove children
			var json = eval('('+data+')');
		 	$.each(json, function(key, value) {
		 		$("#" + value).remove();
		 	});
		 	
		 	callback();
	   },'url':'./index.php?r=x3dInteraction/removeLayer&id=' + id,'cache':false});
}

//SHOW LAYER
function leafShowLayerById(layerId) {
	showLayerById(layerId);
}

function layerShowLayerById(layerId) {
	showLayerById(layerId);
}

function layerExpandAllById(layerId) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	      
	      showLayerDetailsById(layerId);
	},'url':'./index.php?r=x3dInteraction/expandAllGraph&id=' + layerId,'cache':false});
}

function showLayerById(id) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	      
	      showLayerDetailsById(id);
	   },'url':'./index.php?r=x3dInteraction/showLayerGraph&id=' + id,'cache':false});
}