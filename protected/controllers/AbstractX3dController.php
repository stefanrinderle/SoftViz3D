<?php

class AbstractX3dController extends BaseController {
	
	public function actionGetLayer($id = null) {
		$root = TreeElement::model()->findByPk($id);
	
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'tree'
		));
	}
	
	public function actionGetLayerInfo($id = null) {
		$this->widget('application.widgets.sidebar.LayerInfo', array('layerId' => $id));
	}
	
	public function actionGetLeafInfo($id = null) {
		$this->widget('application.widgets.sidebar.LeafInfo', array('leafId' => $id));
	}
	
}