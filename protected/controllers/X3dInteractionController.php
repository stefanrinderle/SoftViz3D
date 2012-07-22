<?php

class X3dInteractionController extends BaseController {
	
	/** Interaction **/
	public function actionGetLayerDetails($id = null) {
		$this->widget('application.widgets.sidebar.LayerDetails', array('layerId' => $id));
	}
	
	public function actionGetLeafDetails($id = null) {
		$this->widget('application.widgets.sidebar.LeafDetails', array('leafId' => $id));
	}
	
	public function actionShowLayer($id = null) {
		$root = InputNode::model()->findByPk($id);
		$root->isVisible = 1;
		$root->save();
	
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'tree'
		));
	}
	
	public function actionShowLayerGraph($id = null) {
		$root = InputNode::model()->findByPk($id);
		$root->isVisible = 1;
		$root->save();
	
		$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
				'layer' => $root, 'type' => 'graph'
		));
	}
	
	public function actionExpandAll($id = null) {
		$root = InputNode::model()->findByPk($id);
		
		if (!$root->isVisible) {
			$root->isVisible = 1;
			$root->save();
			
			$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
					'layer' => $root, 'type' => 'tree'
			));
		}
		
		$children = $this->retrieveAllChildrenLayers($id);
		
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
	
	public function actionExpandAllGraph($id = null) {
		$root = InputNode::model()->findByPk($id);
	
		if (!$root->isVisible) {
			$root->isVisible = 1;
			$root->save();
				
			$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
					'layer' => $root, 'type' => 'graph'
			));
		}
	
		$children = $this->retrieveAllChildrenLayers($id);
	
		foreach($children as $child) {
			if (!$child->isVisible) {
				$this->widget('application.widgets.x3dom.X3domLayerWidget',array(
						'layer' => $child, 'type' => 'graph'
				));
	
				$child->isVisible = 1;
				$child->save();
			}
		}
	}
	
	/**
	 * Returns all children layer objects (recursive) starting with the given layerId.
	 */
	private function retrieveAllChildrenLayers($layerId) {
		$result = array();
	
		$children = InputNode::model()->findAllByAttributes(array('parent_id'=>$layerId));
	
		foreach ($children as $child) {
			array_push($result, $child);
			$result = array_merge($result, $this->retrieveAllChildrenLayers($child->id));
		}
	
		return $result;
	}
	
	/**
	 * This will deliver all nodes of the current layer and
	 * all child leafs and layers.
	 */
	public function actionRemoveLayer($id = null) {
		$root = InputNode::model()->findByPk($id);
		$root->isVisible = 0;
		$root->save();
	
		$result = $this->hideAllChildrenLayers($id);
	
		echo json_encode($result);
	}
	
	private function hideAllChildrenLayers($layerId) {
		$result = array();
	
		$layers = InputNode::model()->findAllByAttributes(array('parent_id'=>$layerId));
		foreach ($layers as $layer) {
			array_push($result, $layer->id);
			$result = array_merge($result, $this->hideAllChildrenLayers($layer->id));
				
			$layer->isVisible = 0;
			$layer->save();
		}
		
		$leafs = InputTreeElement::model()->findAllByAttributes(array('parent_id'=>$layerId));
		foreach ($leafs as $leaf) {
			array_push($result, $leaf->id);
		}
		
		$edges = InputDependency::model()->findAllByAttributes(array('parent_id'=>$layerId));
		foreach ($edges as $edge) {
			array_push($result, $edge->id);
		}
	
		return $result;
	}
	
}