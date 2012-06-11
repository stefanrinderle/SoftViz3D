<?php

class BaseX3dController extends BaseController {
	
	private $currentDepth;
	private $maxDepth;
	
	public function init() {
		$criteria = new CDbCriteria;
		$criteria->select='MAX(level) as maxdepth';
		$maxRecord = TreeElement::model()->find($criteria);
		
		$this->currentDepth = $maxRecord->maxdepth;
		$this->maxDepth = $maxRecord->maxdepth;
	}
	
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
	
	public function actionGetLayerChildren($id = null) {
		$children = TreeElement::model()->findAllByAttributes(array('parent_id'=>$id, 'isLeaf'=>1), array("select" => "id"));
	
		$result = array();
		foreach ($children as $child) {
			array_push($result, $child->id);
		}
	
		echo json_encode($result);
	}
	
// 	public function actionGetAllElementsInLayer($depth) {
// 		$elements = TreeElement::model()->findAllByAttributes(array('level'=>$depth));
		
// 		$result = array();
// 		foreach ($elements as $element) {
// 			array_push($result, $element->id);
// 		}
		
// 		echo json_encode($result);
// 	}
	
}