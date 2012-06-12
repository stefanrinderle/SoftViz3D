// REMOVE LAYER
function leafRemoveLayerById(layerId, leafId) {
	removeLayerById(layerId, function() {
		reloadDetails(leafId, "leaf");
	});
}

function layerRemoveLayerById(layerId) {
	removeLayerById(layerId, function() {
		reloadDetails(layerId, "layer");
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
	   },'url':'./index.php?r=baseX3d/removeLayer&id=' + id,'cache':false});
}

//SHOW LAYER
function leafShowLayerById(layerId, leafId) {
	showLayerById(layerId, function() {
		reloadDetails(leafId, "leaf");
	});
}

function layerShowLayerById(layerId) {
	showLayerById(layerId, function() {
		reloadDetails(layerId, "layer");
	});
}

function layerExpandAllById(layerId) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	      
	      reloadDetails(layerId, "layer");
	},'url':'./index.php?r=baseX3d/expandAll&id=' + layerId,'cache':false});
}

function showLayerById(id, callback) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	      
	      callback();
	   },'url':'./index.php?r=baseX3d/showLayer&id=' + id,'cache':false});
}

function reloadDetails(id, type) {
	if (type == "layer") {
 		showLayerDetailsById(id);
 	} else if (type == "leaf") {
 		showLeafDetailsById(id);
 	} else {
 		alert(type);
 	}
}

//function removeLayerByDepth(depth) {
//	jQuery.ajax({'success':function(data) {
//		var json = eval('('+data+')');
//	 	$.each(json, function(key, value) {
//	 		$("#" + value).remove();
//	 	});
//   },'url':'./index.php?r=baseX3d/getAllElementsInLayer&depth=' + depth,'cache':false});
//}