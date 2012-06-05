function removeNode()
    {
        var ot = document.getElementById('ernst');
        for (var i = 0; i < ot.childNodes.length; i++) {
        	// check if we have a real X3DOM Node; not just e.g. a Text-tag
        	if (ot.childNodes[i].nodeType === Node.ELEMENT_NODE) {
        		ot.removeChild(ot.childNodes[i]);
  				break;
  			}
  		}
        
        return false;
    };