function removeLayerById(id, reloadType) {
	jQuery.ajax({'success':function(data) {
			// remove layer
			$("#" + id).remove();
			
			// remove children
			var json = eval('('+data+')');
		 	$.each(json, function(key, value) {
		 		$("#" + value).remove();
		 	});
		 	
		 	reloadDetails(id, reloadType);
	   },'url':'./index.php?r=baseX3d/removeLayer&id=' + id,'cache':false});
}

function showLayerById(id, reloadType) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	      
	      reloadDetails(id, reloadType);
	   },'url':'./index.php?r=baseX3d/showLayer&id=' + id,'cache':false});
}

function reloadDetails(id, type) {
	if (type == "layer") {
 		showLayerInformationById(id);
 	} else if (type == "leaf") {
 		showLeafInformationById(id);
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