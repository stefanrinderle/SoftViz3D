function removeLayerById(id) {
	jQuery.ajax({'success':function(data) {
			$("#" + id).remove();
			
			var json = eval('('+data+')');
		 	$.each(json, function(key, value) {
		 		$("#" + value).remove();
		 	});
	   },'url':'./index.php?r=baseX3d/getLayerChildren&id=' + id,'cache':false});
}

function showLayerById(id) {
	jQuery.ajax({'success':function(data) {
	      $("#x3dSceneWrapper").append(data);
	   },'url':'./index.php?r=baseX3d/getLayer&id=' + id,'cache':false});
}

//function removeLayerByDepth(depth) {
//	jQuery.ajax({'success':function(data) {
//		var json = eval('('+data+')');
//	 	$.each(json, function(key, value) {
//	 		$("#" + value).remove();
//	 	});
//   },'url':'./index.php?r=baseX3d/getAllElementsInLayer&depth=' + depth,'cache':false});
//}