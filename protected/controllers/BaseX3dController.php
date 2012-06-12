<?php

class BaseX3dController extends BaseController {
	
	public function init() {
// 		$criteria = new CDbCriteria;
// 		$criteria->select='MAX(level) as maxdepth';
// 		$maxRecord = TreeElement::model()->find($criteria);
		
// 		$this->currentDepth = $maxRecord->maxdepth;
// 		$this->maxDepth = $maxRecord->maxdepth;
	}
	
	public function actionGetLayerDetails($id = null) {
		$this->widget('application.widgets.sidebar.LayerDetails', array('layerId' => $id));
	}
	
	public function actionGetLeafDetails($id = null) {
		$this->widget('application.widgets.sidebar.LeafDetails', array('leafId' => $id));
	}
	
	public function actionShowLayer($id = null) {
		$root = TreeElement::model()->findByPk($id);
		$root->isVisible = 1;
		$root->save();
	
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'tree'
		));
	}
	
	public function actionExpandAll($id = null) {
		$root = TreeElement::model()->findByPk($id);
		
		if (!$root->isVisible) {
			$root->isVisible = 1;
			$root->save();
			
			$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
					'layer' => $root, 'type' => 'tree'
			));
		}
		
		$children = $this->showChildren($id);
		
		foreach($children as $child) {
			if (!$child->isVisible) {
				$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
						'layer' => $child, 'type' => 'tree'
				));
				
				$child->isVisible = 1;
				$child->save();
			}
		}
	}
	
	private function showChildren($layerId) {
		$result = array();
		
		$children = TreeElement::model()->findAllByAttributes(array('parent_id'=>$layerId, 'isLeaf'=>0));
		
		foreach ($children as $child) {
			array_push($result, $child);
			$result = array_merge($result, $this->showChildren($child->id));
		}
		
		return $result;
	}
	
	private function removeChildren($layerId) {
		$result = array();
	
		$children = TreeElement::model()->findAllByAttributes(array('parent_id'=>$layerId));
		
		foreach ($children as $child) {
			if ($child->isLeaf) {
				array_push($result, $child->id);
			} else {
				array_push($result, $child->id);
				$result = array_merge($result, $this->removeChildren($child->id));
				
				$child->isVisible = 0;
				$child->save();
			}
		}
	
		return $result;
	}
	
	/**
	 * This will deliver all nodes of the current layer and
	 * all child leafs and layers.
	 */
	public function actionRemoveLayer($id = null) {
		$root = TreeElement::model()->findByPk($id);
		$root->isVisible = 0;
		$root->save();
		
		$result = $this->removeChildren($id);
	
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