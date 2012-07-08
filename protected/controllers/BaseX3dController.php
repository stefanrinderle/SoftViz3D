<?php

class BaseX3dController extends BaseController {
	
	protected function loadFiletoDb() {
		$filename = Yii::app()->basePath . Yii::app()->params['currentResourceFile'];
		
		try {
			/* reset database */
			TreeElement::model()->deleteAll();
			EdgeElement::model()->deleteAll();
				
			// STEP 2: Load input dot file into db
			$result = Yii::app()->ownDotParser->parse($filename);
			
			$result[rootId] = $this->removeStartingEmptyLayers($result[rootId]);
			return $result;
		} catch (Exception $e) {
			$exception = $e;
			Yii::app()->user->setFlash('error', 'Input file parsing failed: ' . $e->getMessage());
			//TODO render another layout file and exit
		}
	}
	
	private function removeStartingEmptyLayers($rootId) {
		$childLayer = LayerElement::model()->findAllByAttributes(
				array('parent_id'=>$rootId));
			
		while (count($childLayer) < 2) {
			$rootId = $childLayer[0]->id;
			$childLayer = LayerElement::model()->findAllByAttributes(
					array('parent_id'=>$rootId));
		}
		
		return $rootId;
	}
	
	/** Interction things **/
	public function actionGetLayerDetails($id = null) {
		$this->widget('application.widgets.sidebar.LayerDetails', array('layerId' => $id));
	}
	
	public function actionGetLeafDetails($id = null) {
		$this->widget('application.widgets.sidebar.LeafDetails', array('leafId' => $id));
	}
	
	public function actionShowLayer($id = null) {
		$root = LayerElement::model()->findByPk($id);
		$root->isVisible = 1;
		$root->save();
	
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'tree'
		));
	}
	
	public function actionExpandAll($id = null) {
		$root = LayerElement::model()->findByPk($id);
		
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
		
		$children = LayerElement::model()->findAllByAttributes(array('parent_id'=>$layerId));
		
		foreach ($children as $child) {
			array_push($result, $child);
			$result = array_merge($result, $this->showChildren($child->id));
		}
		
		return $result;
	}
	
	private function removeChildren($layerId) {
		$result = array();
	
		$layers = LayerElement::model()->findAllByAttributes(array('parent_id'=>$layerId));
		foreach ($layers as $layer) {
			array_push($result, $layer->id);
			$result = array_merge($result, $this->removeChildren($layer->id));
				
			$layer->isVisible = 0;
			$layer->save();
		}
		
		$leafs = TreeElement::model()->findAllByAttributes(array('parent_id'=>$layerId));
		foreach ($leafs as $leaf) {
			array_push($result, $leaf->id);
		}
	
		return $result;
	}
	
	/**
	 * This will deliver all nodes of the current layer and
	 * all child leafs and layers.
	 */
	public function actionRemoveLayer($id = null) {
		$root = LayerElement::model()->findByPk($id);
		$root->isVisible = 0;
		$root->save();
		
		$result = $this->removeChildren($id);
	
		echo json_encode($result);
	}
	
}